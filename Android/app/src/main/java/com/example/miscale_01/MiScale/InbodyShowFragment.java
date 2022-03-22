package com.example.miscale_01.MiScale;

import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;

import com.example.miscale_01.R;
import com.example.miscale_01.SmartPT;

public class InbodyShowFragment extends Fragment {

    private TextView weightTv, bmiTv, waterTv, muscleTv, fatTv;
    private SmartPT smartPT;
    private String weight, bmi, water, muscle, fat;

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        View root = inflater.inflate(R.layout.fragment_inbody_show, container, false);
        setHasOptionsMenu(true);

        weightTv = root.findViewById(R.id.weight_tv);
        bmiTv = root.findViewById(R.id.bmi_tv);
        waterTv = root.findViewById(R.id.water_tv);
        muscleTv = root.findViewById(R.id.muscle_tv);
        fatTv = root.findViewById(R.id.fat_tv);

        weight = "Weight: ";
        bmi = "BMI: ";
        water = "Water: ";
        muscle = "Muscle: ";
        fat = "Fat: ";

        smartPT = SmartPT.getInstance();

        showInbody();

        return root;
    }

    private void showInbody(){
        ScaleData scaleData = smartPT.getScaleData();

        if(scaleData == null){
            weightTv.setText(weight+"Empty");
            bmiTv.setText(bmi+"Empty");
            waterTv.setText(water+"Empty");
            muscleTv.setText(muscle+"Empty");
            fatTv.setText(fat+"Empty");
        }
        else{
            weightTv.setText(weight+Float.toString(scaleData.weight)+"kg");
            bmiTv.setText(bmi+"키, 몸무게 필요");
            waterTv.setText(water+Float.toString(scaleData.water)+"%");
            muscleTv.setText(muscle+Float.toString(scaleData.muscle)+"%");
            fatTv.setText(fat+Float.toString(scaleData.fat)+"%");
        }
    }
}
