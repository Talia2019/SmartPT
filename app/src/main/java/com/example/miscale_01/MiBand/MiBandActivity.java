package com.example.miscale_01.MiBand;

import android.Manifest;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.net.Uri;
import android.os.Bundle;
import android.provider.Settings;
import android.util.Log;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.app.ActivityCompat;
import androidx.core.content.ContextCompat;

import com.example.miscale_01.R;
import com.google.android.gms.auth.api.signin.GoogleSignIn;
import com.google.android.gms.fitness.Fitness;
import com.google.android.gms.fitness.FitnessActivities;
import com.google.android.gms.fitness.FitnessOptions;
import com.google.android.gms.fitness.data.Bucket;
import com.google.android.gms.fitness.data.DataPoint;
import com.google.android.gms.fitness.data.DataSet;
import com.google.android.gms.fitness.data.DataType;
import com.google.android.gms.fitness.data.Field;
import com.google.android.gms.fitness.data.Value;
import com.google.android.gms.fitness.request.DataReadRequest;
import com.google.android.gms.fitness.result.DataReadResponse;
import com.google.android.gms.tasks.OnCompleteListener;
import com.google.android.gms.tasks.OnFailureListener;
import com.google.android.gms.tasks.OnSuccessListener;
import com.google.android.gms.tasks.Task;

import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;
import java.util.List;
import java.util.concurrent.TimeUnit;

public class MiBandActivity extends AppCompatActivity {

    public static final String TAG = "StepCounter";
    private static final int PERMISSION_ALL = 1;
    private static final int REQUEST_OAUTH_REQUEST_CODE = 0x1001;
    private static final int SINGLE_PERMISSION = 1004;  //권한변수

    static String[] categories = {"Swimming", "Biking", "Jumping Rope", "Walking", "Running", "Nothing", "Somethingelse"};
    private int calorie = 0;
    private long step = 0;
    private int distance = 0;
    private static int activity_min[] = new int[7];
    private static float activity_cal[] = new float[7];

    private FitnessOptions fitnessOptions;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        checkPermission();
        initializeHealth();

        setContentView(R.layout.activity_mi_band);
    }

    public void checkPermission(){
        String[] PERMISSIONS = {
                Manifest.permission.ACTIVITY_RECOGNITION,
                Manifest.permission.ACCESS_FINE_LOCATION
        };
        //권한없음
        if((ContextCompat.checkSelfPermission(this, PERMISSIONS[0]) != PackageManager.PERMISSION_GRANTED) ||
                (ContextCompat.checkSelfPermission(this, PERMISSIONS[1]) != PackageManager.PERMISSION_GRANTED)){
            ActivityCompat.requestPermissions(this, PERMISSIONS, PERMISSION_ALL);
            Log.e(TAG, "허가안됐음");
        }
        else{}//권한있음
    }

    @Override
    public void onRequestPermissionsResult(int requestCode, @NonNull String[] permissions, @NonNull int[] grantResults) {
        super.onRequestPermissionsResult(requestCode, permissions, grantResults);
        switch (requestCode) {
            case PERMISSION_ALL:
                if (grantResults.length > 0){// && grantResults[0] == PackageManager.PERMISSION_GRANTED) {
                    for(int i=0;i<grantResults.length;i++){
                        if(grantResults[i] == PackageManager.PERMISSION_DENIED){
                            AlertDialog.Builder alertDialog = new AlertDialog.Builder(this);
                            alertDialog.setTitle("앱 권한");
                            alertDialog.setMessage("해당 앱의 원할한 기능을 이용하시려면 애플리케이션 정보>권한> 에서 모든 권한을 허용해 주십시오");
                            // 권한설정 클릭시 이벤트 발생
                            alertDialog.setPositiveButton("권한설정",
                                    new DialogInterface.OnClickListener() {
                                        public void onClick(DialogInterface dialog, int which) {
                                            Intent intent = new Intent(Settings.ACTION_APPLICATION_DETAILS_SETTINGS).setData(Uri.parse("package:" + getApplicationContext().getPackageName()));
                                            startActivity(intent);
                                            dialog.cancel();
                                        }
                                    });
                            //취소
                            alertDialog.setNegativeButton("취소",
                                    new DialogInterface.OnClickListener() {
                                        public void onClick(DialogInterface dialog, int which) {
                                            dialog.cancel();
                                        }
                                    });
                            alertDialog.show();
                        }
                    }
                }
                return;
        }
    }

    public void initializeHealth() {

        fitnessOptions =
                FitnessOptions.builder()
//                        .addDataType(DataType.TYPE_STEP_COUNT_CUMULATIVE)
                        .addDataType(DataType.TYPE_CALORIES_EXPENDED)
                        .addDataType(DataType.TYPE_STEP_COUNT_DELTA)
                        .addDataType(DataType.TYPE_ACTIVITY_SEGMENT)
                        .addDataType(DataType.TYPE_DISTANCE_DELTA)
                        .build();

        if (!GoogleSignIn.hasPermissions(GoogleSignIn.getLastSignedInAccount(this), fitnessOptions)) {
            //구글로그인안된경우
            GoogleSignIn.requestPermissions(
                    this,
                    REQUEST_OAUTH_REQUEST_CODE,
                    GoogleSignIn.getLastSignedInAccount(this),
                    fitnessOptions);
            Log.i(TAG, "initializeHealth: Fitness Counter Background Permission denied ");
        }
        else {
            //구글로그인됨
            initializeStep();
            initializeCalorie();
            initializeDistance();
            readHistoryData();
        }
    }

    public void initializeCalorie() {
        //칼로리권한확인
        Fitness.getRecordingClient(this , GoogleSignIn.getLastSignedInAccount(this))
                .subscribe(DataType.TYPE_CALORIES_EXPENDED)
                .addOnCompleteListener(
                        new OnCompleteListener<Void>() {
                            @Override
                            public void onComplete(@NonNull Task<Void> task) {
                                if (task.isSuccessful()) {
                                    Log.i(TAG, "Successfully subscribed for calorie!");
                                    readCalorieData();//false
                                } else {
                                    Log.w(TAG, "There was a problem subscribing.", task.getException());
                                }
                            }
                        })
                .addOnFailureListener(new OnFailureListener() {
                    @Override
                    public void onFailure(@NonNull Exception e) {
                        Log.i(TAG, "There was a problem subscribing."+ e.getMessage());
                    }
                });
    }

    public void readCalorieData() {
        Fitness.getHistoryClient(this, GoogleSignIn.getLastSignedInAccount(this))
                .readDailyTotal(DataType.TYPE_CALORIES_EXPENDED)
                .addOnSuccessListener(
                        new OnSuccessListener<DataSet>() {
                            @Override
                            public void onSuccess(DataSet dataSet) {
                                int total =
                                        (int) (dataSet.isEmpty()
                                                ? 0
                                                : dataSet.getDataPoints().get(0).getValue(Field.FIELD_CALORIES).asFloat());
                                Log.i(TAG, "Total calories = " + total);
                                calorie = total;
                            }
                        })
                .addOnFailureListener(
                        new OnFailureListener() {
                            @Override
                            public void onFailure(@NonNull Exception e) {
                                Log.w(TAG, "There was a problem getting the calorie count.", e);
                            }
                        });

    }

    public void initializeStep() {
        Fitness.getRecordingClient(this, GoogleSignIn.getLastSignedInAccount(this))
                .subscribe(DataType.TYPE_STEP_COUNT_DELTA)
                .addOnCompleteListener(
                        new OnCompleteListener<Void>() {
                            @Override
                            public void onComplete(@NonNull Task<Void> task) {
                                if (task.isSuccessful()) {
                                    Log.i(TAG, "Successfully subscribed!");
                                    readStepCountData();//false, false, null
                                } else {
                                    Log.w(TAG, "There was a problem subscribing.", task.getException());
                                }
                            }
                        })
                .addOnFailureListener(new OnFailureListener() {
                    @Override
                    public void onFailure(@NonNull Exception e) {
                        Log.w(TAG, "There was a problem subscribing. steps"+ e.getMessage());
                    }
                });

    }

    public void readStepCountData() {
//        Fitness.getHistoryClient(this, GoogleSignIn.getSignedInAccountFromIntent(this))
//                .readData()
        Fitness.getHistoryClient(this, GoogleSignIn.getLastSignedInAccount(this))
                .readDailyTotal(DataType.TYPE_STEP_COUNT_DELTA)
                .addOnSuccessListener(
                        new OnSuccessListener<DataSet>() {
                            @Override
                            public void onSuccess(DataSet dataSet) {
                                long total =
                                        dataSet.isEmpty()
                                                ? 0
                                                : dataSet.getDataPoints().get(0).getValue(Field.FIELD_STEPS).asInt();
                                step = total;
                                Log.i(TAG, "Total steps = " + total);
                            }
                        })
                .addOnFailureListener(
                        new OnFailureListener() {
                            @Override
                            public void onFailure(@NonNull Exception e) {
                                Log.w(TAG, "There was a problem getting the step count.", e);
                            }
                        });
    }

    public static DataReadRequest queryFitnessData(){
//        TimeZone jst = TimeZone.getTimeZone ("JST");
        Calendar cal = Calendar.getInstance();
        Date now = new Date();
        cal.setTime(now);
        long endTime = cal.getTimeInMillis();
        cal.set(Calendar.HOUR_OF_DAY, 00);
        cal.set(Calendar.MINUTE, 0);
        cal.set(Calendar.SECOND, 0);
//        cal.add(Calendar.WEEK_OF_YEAR, -1);
//        cal.add(Calendar.DAY_OF_YEAR, 6);
        long startTime = cal.getTimeInMillis();
//
//        cal.set(Calendar.HOUR_OF_DAY, 23);
//        cal.set(Calendar.MINUTE, 59);
//        cal.set(Calendar.SECOND, 59);
//        endTime = cal.getTimeInMillis();

        java.text.DateFormat dateFormat = DateFormat.getDateInstance();
//        Log.i(TAG, "Range Start: " + dateFormat.format(startTime));
//        Log.i(TAG, "Range End: " + dateFormat.format(endTime));
        SimpleDateFormat format1 = new SimpleDateFormat("yyyy년 MM월dd일 HH시mm분ss초");
        Log.i(TAG, "Range Start" + format1.format(startTime));
        Log.i(TAG, "Range End" + format1.format(endTime));

        DataReadRequest readRequest =
                new DataReadRequest.Builder()
                        .aggregate(DataType.TYPE_ACTIVITY_SEGMENT)
                        .aggregate(DataType.TYPE_CALORIES_EXPENDED)
                        .bucketByActivityType(1, TimeUnit.MILLISECONDS)
                        .setTimeRange(startTime, endTime, TimeUnit.MILLISECONDS)
                        .build();

        return readRequest;
    }

    private Task<DataReadResponse> readHistoryData() {
        DataReadRequest readRequest = queryFitnessData();

        // Invoke the History API to fetch the data with the query
        return Fitness.getHistoryClient(this, GoogleSignIn.getLastSignedInAccount(this))
                .readData(readRequest)
                .addOnSuccessListener(
                        new OnSuccessListener<DataReadResponse>() {
                            @Override
                            public void onSuccess(DataReadResponse dataReadResponse) {
                                //Log.e(TAG, "NO PROBLEM");
                                printData(dataReadResponse);
                            }
                        })
                .addOnFailureListener(
                        new OnFailureListener() {
                            @Override
                            public void onFailure(@NonNull Exception e) {
                                Log.e(TAG, "There was a problem reading history data.", e);
                            }
                        });
    }

    static void dumpDataSet(DataSet dataSet, String activity) {
        if (dataSet.isEmpty()) {
            Log.e(TAG, "빈 셋");
            return;
        }

        SimpleDateFormat dateFormat = new SimpleDateFormat();
        for (DataPoint dp : dataSet.getDataPoints()) {
            String s = String.format("Data point: %s -> %s   %s",
                    dp.getDataType().getName(),
                    dateFormat.format(dp.getStartTime(TimeUnit.MILLISECONDS)),
                    dateFormat.format(dp.getEndTime(TimeUnit.MILLISECONDS)));
            Log.i(TAG, s);

            if(dp.getDataType().equals(DataType.TYPE_CALORIES_EXPENDED))
                setActivityCal(activity, dp.getValue(Field.FIELD_CALORIES).asFloat());
            else
                setActivityMin(dp.getValue(Field.FIELD_ACTIVITY).asInt(), dp.getValue(Field.FIELD_DURATION).asInt());
            for(Field field : dp.getDataType().getFields()) {
                String name = field.getName();
                Value value = dp.getValue(field);
                Log.i(TAG, "\t" + name + " = " + value.toString());
            }
        }
    }

    public static void printData(DataReadResponse dataReadResult) {
        int bucketSize = dataReadResult.getBuckets().size();
        if (bucketSize > 0) {
            Log.i(TAG, "Number of returned buckets of DataSets is: " + bucketSize);
            for (Bucket bucket : dataReadResult.getBuckets()) {
                String bucketActivity = bucket.getActivity();
                List<DataSet> dataSets = bucket.getDataSets();
                for (DataSet dataSet : dataSets) {
                    dumpDataSet(dataSet, bucketActivity);
                }
//                if(bucketActivity.contains(FitnessActivities.WALKING) ||
//                        bucketActivity.contains(FitnessActivities.RUNNING)){
//                    Log.e(TAG, "bucket type->" + bucket.getActivity());
//                    dataSets = bucket.getDataSets();
//                    for (DataSet dataSet : dataSets) {
//                        dumpDataSet(dataSet, bucketActivity);
//                    }
//                }
                //Log.e(TAG, "BurnedCalories->" + String.valueOf(expendedCalories));
            }
        } else if (dataReadResult.getDataSets().size() > 0) {
            Log.i(TAG, "Number of returned DataSets is: " + dataReadResult.getDataSets().size());
            for (DataSet dataSet : dataReadResult.getDataSets()) {
                dumpDataSet(dataSet, "??");
            }
        }
        else{
            Log.i(TAG, "Nothing to print");
        }
    }

    public void initializeDistance() {
        Fitness.getRecordingClient(this , GoogleSignIn.getLastSignedInAccount(this))
                .subscribe(DataType.TYPE_DISTANCE_DELTA)
                .addOnCompleteListener(
                        new OnCompleteListener<Void>() {
                            @Override
                            public void onComplete(@NonNull Task<Void> task) {
                                if (task.isSuccessful()) {
                                    Log.i(TAG, "Successfully subscribed for distance!");
                                    readDistanceData();//false
                                } else {
                                    Log.w(TAG, "There was a problem distance subscribing.", task.getException());
                                }
                            }
                        })
                .addOnFailureListener(new OnFailureListener() {
                    @Override
                    public void onFailure(@NonNull Exception e) {
                        Log.i(TAG, "There was a problem distance subscribing."+ e.getMessage());
                    }
                });
    }


    public void readDistanceData() {
        Fitness.getHistoryClient(this, GoogleSignIn.getLastSignedInAccount(this))
                .readDailyTotal(DataType.TYPE_DISTANCE_DELTA)
                .addOnSuccessListener(
                        new OnSuccessListener<DataSet>() {
                            @Override
                            public void onSuccess(DataSet dataSet) {
                                int total =
                                        (int) (dataSet.isEmpty()
                                                ? 0
                                                : dataSet.getDataPoints().get(0).getValue(Field.FIELD_DISTANCE).asFloat());
                                distance = total;
                                Log.i(TAG, "Total distance = " + total);
                            }
                        })
                .addOnFailureListener(
                        new OnFailureListener() {
                            @Override
                            public void onFailure(@NonNull Exception e) {
                                Log.w(TAG, "There was a problem getting the distance count.", e);
                            }
                        });

    }

    private static int getCategory(int activity){
        if(activity == 3)   //nothing
            return 5;
        else if(activity == 82)
            return 0;//"Swimming";
        else if(activity == 1)
            return 1;//"Biking";
        else if(activity == 39)
            return 2;//"Jumping Rope";
        else if(activity == 8)
            return 4;//Running
        else if(activity == 7)    //walking(7)
            return 3;//"Walking";
        else
            return 5;
    }
    private static int getCategory(String activity){
        if(activity.contains(FitnessActivities.SWIMMING))
            return 0;
        else if(activity.contains(FitnessActivities.BIKING))
            return 1;
        else if(activity.contains(FitnessActivities.JUMP_ROPE))
            return 2;
        else if(activity.contains(FitnessActivities.RUNNING))
            return 4;
        else if(activity.contains(FitnessActivities.WALKING))
            return 3;
        else
            return 5;
    }

    private static void setActivityMin(int category, int millisecond){
        int c = getCategory(category);
        activity_min[c] += millisecond/1000/60;
        for(int i=0;i<5;i++){
            Log.i(TAG, "활동 : "+categories[i] +"분 : "+ activity_min[i]);
        }
    }
    private static void setActivityCal(String activity, float calorie){
        int c = getCategory(activity);
        activity_cal[c] += calorie;
        for(int i=0;i<5;i++){
            Log.i(TAG, "활동 : "+categories[i] +"칼로리 : "+ activity_cal[i]);
        }
    }

}