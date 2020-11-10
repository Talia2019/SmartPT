package com.example.miscale_01.MiScale;

import android.app.Application;

import java.util.Date;

public class ScaleData extends Application {
    public long id;
    public int user_id;
    public Date date_time;
    public float weight;
    public float fat;
    public float water;
    public float muscle;
    public float waist;
    public float hip;
    public String comment;
    public float bone;
    public float visceralFat;

    public ScaleData()
    {
        id = -1;
        user_id = -1;
        date_time = new Date();
        weight = -1.0f;
        fat = -1.0f;
        water = -1.0f;
        muscle = -1.0f;
        waist = -1.0f;
        hip = -1.0f;
        bone = -1.0f;
        visceralFat = -1.0f;
        comment = new String();
    }

    @Override
    public String toString()
    {
        return "ID : " + id + " USER_ID: " + user_id + " DATE_TIME: " + date_time.toString() + " WEIGHT: " + weight + " FAT: " + fat + " WATER: " + water + " MUSCLE: " + muscle + " WAIST: " + waist + " HIP: " + hip + "BONE:" + bone + " COMMENT: " + comment;
    }
}
