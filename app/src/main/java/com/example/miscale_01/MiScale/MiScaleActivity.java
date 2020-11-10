package com.example.miscale_01.MiScale;

import android.bluetooth.BluetoothAdapter;
import android.bluetooth.BluetoothManager;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.os.Handler;
import android.os.Message;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.fragment.app.Fragment;
import androidx.fragment.app.FragmentManager;
import androidx.fragment.app.FragmentTransaction;

import com.example.miscale_01.PreferenceManager;
import com.example.miscale_01.R;
import com.example.miscale_01.SmartPT;
import com.example.miscale_01.bluetooth.BluetoothCommunication;

import timber.log.Timber;

public class MiScaleActivity extends AppCompatActivity {


    private Context context;

    private static final int ENABLE_BLUETOOTH_REQUEST = 102;
    private static final int REQUEST_ENABLE_BT = 0;
    private static final int BT_ON = 1;
    private static final int BT_OFF = 2;
    private static final int BT_SEARCHING = 3;

    private SmartPT smartPT = SmartPT.getInstance();
    private int btState;
    private int userID;

    private TextView deviceNameTv, deviceAddressTv;
    private Button bltBtn, inbodyBtn;
    private ImageView bltIv;

    //    private static Button bluetoothStatus;
    private static boolean firstAppStart = true;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_mi_scale);
        boolean isFirst = PreferenceManager.getIsFirst(context);
        if(isFirst == true){
            Log.d("SmartPT", "뉴비");
            createID();
        }else{
            Log.d("SmartPT", "기존유저");
            userID = PreferenceManager.getID(context);
        }

        FragmentManager fm = getSupportFragmentManager();
        FragmentTransaction fragmentTransaction = fm.beginTransaction();
        fragmentTransaction.add(R.id.fragment, new BluetoothSettingsFragment());
        fragmentTransaction.commit();

        deviceNameTv = findViewById(R.id.device_name_tv);
        deviceAddressTv = findViewById(R.id.device_address_tv);
        bltBtn = findViewById(R.id.blt_btn);
        inbodyBtn = findViewById(R.id.inbody_btn);
        bltIv = findViewById(R.id.blt_iv);

        deviceNameTv.setText(smartPT.getDeviceName());
        deviceAddressTv.setText(smartPT.getDeviceAddress());

        BluetoothManager bluetoothManager = (BluetoothManager) getSystemService(BLUETOOTH_SERVICE);
        boolean hasBluetooth = bluetoothManager.getAdapter() != null;

        if(!hasBluetooth){
            Log.d("bluetooth", "Bluetooth is not available");
        }
        else if(firstAppStart){
            Log.d("bluetooth", "Bluetooth is available-first");
//            invokeConnectToBluetoothDevice();
            firstAppStart = false;
        }
        else{
            Log.d("bluetooth", "Bluetooth is available");
        }

        final BluetoothAdapter bluetoothAdapter = bluetoothManager.getAdapter();
        if(bluetoothAdapter.isEnabled()){
            btState = BT_ON;
            bltIv.setImageResource(R.drawable.ic_bt_on);
        }
        else{
            btState = BT_OFF;
            bltIv.setImageResource(R.drawable.ic_bl_disabled);
        }
        bltIv.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if(btState == BT_OFF){
                    showToast("Turning on bluetooth");
                    bltIv.setImageResource(R.drawable.ic_bt_on);
                    btState = BT_ON;
                    Intent intent = new Intent(BluetoothAdapter.ACTION_REQUEST_ENABLE);
                    startActivityForResult(intent, REQUEST_ENABLE_BT);
                }else{
                    bluetoothAdapter.disable();
                    btState = BT_OFF;
                    showToast("Turning bluetooth off");
                    bltIv.setImageResource(R.drawable.ic_bl_disabled);
                }
            }
        });

        bltBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                switchFragemnt(new BluetoothSettingsFragment());
                invokeConnectToBluetoothDevice();
            }
        });
        inbodyBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                switchFragemnt(new InbodyShowFragment());
            }
        });
    };

    private void switchFragemnt(Fragment fr){
        FragmentManager fm = getSupportFragmentManager();
        FragmentTransaction fragmentTransaction = fm.beginTransaction();
        fragmentTransaction.replace(R.id.fragment, fr);
        fragmentTransaction.commit();
    }

    private void showToast(String msg){
        Toast.makeText(this, msg, Toast.LENGTH_SHORT).show();
    }

    private void invokeConnectToBluetoothDevice(){
//
//        if (smartPT.getSelectedScaleUserId() == -1) {
////            showNoSelectedUserDialog();
//            Log.d("SmartPT", "NO User");
//            return;
//        }
        String deviceName = smartPT.getDeviceName();
        String hwAddress = smartPT.getDeviceAddress();

        if (!BluetoothAdapter.checkBluetoothAddress(hwAddress)) {
            Toast.makeText(getApplicationContext(), "bluetooth_no_device_set", Toast.LENGTH_SHORT).show();
            return;
        }

        BluetoothManager bluetoothManager = (BluetoothManager) getSystemService(BLUETOOTH_SERVICE);
        if (!bluetoothManager.getAdapter().isEnabled()) {
            bltIv.setImageResource(R.drawable.ic_bl_disabled);
            Intent enableBtIntent = new Intent(BluetoothAdapter.ACTION_REQUEST_ENABLE);
            startActivityForResult(enableBtIntent, ENABLE_BLUETOOTH_REQUEST);
            return;
        }

        Toast.makeText(getApplicationContext(), "info_bluetooth_try_connection" + " " + deviceName, Toast.LENGTH_SHORT).show();
        bltIv.setImageResource(R.drawable.ic_bt_searching);

        if (!smartPT.connectToBluetoothDevice(deviceName, hwAddress, callbackBtHandler)) {
            Toast.makeText(getApplicationContext(), deviceName + " " + "label_bt_device_no_support", Toast.LENGTH_SHORT).show();
        }
    }

    private final Handler callbackBtHandler = new Handler() {
        @Override
        public void handleMessage(Message msg) {

            BluetoothCommunication.BT_STATUS btStatus = BluetoothCommunication.BT_STATUS.values()[msg.what];

            switch (btStatus) {
                case RETRIEVE_SCALE_DATA:
                    SmartPT smartPT = SmartPT.getInstance();
//
//                    if (prefs.getBoolean("mergeWithLastMeasurement", true)) {
//                        if (!openScale.isScaleMeasurementListEmpty()) {
//                            ScaleMeasurement lastMeasurement = openScale.getLastScaleMeasurement();
//                            scaleBtData.merge(lastMeasurement);
//                        }
//                    }

//                    openScale.addScaleMeasurement(scaleBtData, true);
                    break;
                case INIT_PROCESS:
//                    setBluetoothStatusIcon(R.drawable.ic_bluetooth_connection_success);
                    Toast.makeText(getApplicationContext(), "info_bluetooth_init", Toast.LENGTH_SHORT).show();
                    Timber.d("Bluetooth initializing");
                    break;
                case CONNECTION_LOST:
//                    setBluetoothStatusIcon(R.drawable.ic_bluetooth_connection_lost);
                    Toast.makeText(getApplicationContext(), "info_bluetooth_connection_lost", Toast.LENGTH_SHORT).show();
                    Timber.d("Bluetooth connection lost");
                    break;
                case NO_DEVICE_FOUND:
//                    setBluetoothStatusIcon(R.drawable.ic_bluetooth_connection_lost);
                    Toast.makeText(getApplicationContext(), "info_bluetooth_no_device", Toast.LENGTH_SHORT).show();
                    Timber.e("No Bluetooth device found");
                    break;
                case CONNECTION_RETRYING:
//                    setBluetoothStatusIcon(R.drawable.ic_bluetooth_searching);
                    Toast.makeText(getApplicationContext(), "info_bluetooth_no_device_retrying", Toast.LENGTH_SHORT).show();
                    Timber.e("No Bluetooth device found retrying");
                    break;
                case CONNECTION_ESTABLISHED:
//                    setBluetoothStatusIcon(R.drawable.ic_bluetooth_connection_success);
                    Toast.makeText(getApplicationContext(), "info_bluetooth_connection_successful", Toast.LENGTH_SHORT).show();
                    Timber.d("Bluetooth connection successful established");
                    break;
                case CONNECTION_DISCONNECT:
//                    setBluetoothStatusIcon(R.drawable.ic_bluetooth_connection_lost);
                    Toast.makeText(getApplicationContext(), "info_bluetooth_connection_disconnected", Toast.LENGTH_SHORT).show();
                    Timber.d("Bluetooth connection successful disconnected");
                    break;
                case UNEXPECTED_ERROR:
//                    setBluetoothStatusIcon(R.drawable.ic_bluetooth_connection_lost);
                    Toast.makeText(getApplicationContext(), "info_bluetooth_connection_error" + ": " + msg.obj, Toast.LENGTH_SHORT).show();
                    Timber.e("Bluetooth unexpected error: %s", msg.obj);
                    break;
                case SCALE_MESSAGE:
                    try {
                        String toastMessage = String.format(getResources().getString(msg.arg1), msg.obj);
                        Toast.makeText(getApplicationContext(), toastMessage, Toast.LENGTH_LONG).show();
                        Timber.d("Bluetooth scale message: " + toastMessage);
                    } catch (Exception ex) {
                        Timber.e("Bluetooth scale message error: " + ex);
                    }
                    break;
            }
        }
    };

    @Override
    public void onActivityResult(int requestCode, int resultCode, Intent data) {
        super.onActivityResult(requestCode, resultCode, data);

        if (requestCode == ENABLE_BLUETOOTH_REQUEST) {
            if (resultCode == RESULT_OK) {
                invokeConnectToBluetoothDevice();
            }
            else {
                Toast.makeText(this, "Bluetooth " + "_is_not_enable", Toast.LENGTH_SHORT).show();
            }
            return;
        }

        if (resultCode != RESULT_OK || data == null) {
            return;
        }
    }

    private void createID(){
        //9자리 숫자 랜덤 생성 후 DB랑 연결해서 중복확인
        int id = -1;

        //중복확인
        boolean isOverlap = true;
        while(isOverlap){
            isOverlap = false;
            id = (int)(Math.random()*1000000000);
            //if DB의 id값이랑 중복된경우 isOverlap = true;
        }

        PreferenceManager.setID(context, id);
    }
}