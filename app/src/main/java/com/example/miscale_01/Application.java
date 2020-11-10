package com.example.miscale_01;

import timber.log.Timber;

public class Application extends android.app.Application{
    SmartPT smartPT;

    private class TimberLogAdapter extends Timber.DebugTree {
        @Override
        protected boolean isLoggable(String tag, int priority) {
            if (BuildConfig.DEBUG || SmartPT.DEBUG_MODE) {
                return super.isLoggable(tag, priority);
            }
            return false;
        }
    }

    @Override
    public void onCreate() {
        super.onCreate();

        Timber.plant(new TimberLogAdapter());

        // Create OpenScale instance
        SmartPT.createInstance(getApplicationContext());

        // Hold on to the instance for as long as the application exists
        smartPT = SmartPT.getInstance();
    }

}
