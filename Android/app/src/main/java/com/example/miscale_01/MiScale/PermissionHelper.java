package com.example.miscale_01.MiScale;

import android.Manifest;
import android.app.AlertDialog;
import android.app.Dialog;
import android.bluetooth.BluetoothAdapter;
import android.bluetooth.BluetoothManager;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.location.LocationManager;
import android.os.Build;
import android.provider.Settings;
import android.widget.Toast;

import androidx.fragment.app.Fragment;

import static android.content.Context.LOCATION_SERVICE;

public class PermissionHelper {
    public final static int PERMISSIONS_REQUEST_ACCESS_FINE_LOCATION = 1;
    public final static int ENABLE_BLUETOOTH_REQUEST = 2;

    public static boolean requestBluetoothPermission(final Fragment fragment){
        final BluetoothManager bluetoothManager = (BluetoothManager) fragment.getActivity().getSystemService(Context.BLUETOOTH_SERVICE);
        BluetoothAdapter btAdapter = bluetoothManager.getAdapter();

        if(btAdapter == null || !btAdapter.isEnabled()){
            Toast.makeText(fragment.getContext(), "Bluetooth disable", Toast.LENGTH_SHORT).show();

            if (btAdapter != null) {
                Intent enableBtIntent = new Intent(BluetoothAdapter.ACTION_REQUEST_ENABLE);
                fragment.getActivity().startActivityForResult(enableBtIntent, ENABLE_BLUETOOTH_REQUEST);
            }
            return false;
        }

        // Check if Bluetooth 4.x is available
        if (!fragment.getActivity().getPackageManager().hasSystemFeature(PackageManager.FEATURE_BLUETOOTH_LE)) {
            Toast.makeText(fragment.getContext(), "Bluetooth 4.x disable", Toast.LENGTH_SHORT).show();
            return false;
        }

        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.M) {
            String permission_blt_info = "Location permission needed to search for Bluetooth devices. It can be revoked after the device is found.";

            if (fragment.getContext().checkSelfPermission(Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED) {
                AlertDialog.Builder builder = new AlertDialog.Builder(fragment.getActivity());

                builder.setMessage(permission_blt_info)
                        .setTitle("Information")
                        .setPositiveButton("OK", new DialogInterface.OnClickListener() {
                            public void onClick(DialogInterface dialog, int id) {
                                dialog.dismiss();
                                fragment.requestPermissions(new String[]{Manifest.permission.ACCESS_FINE_LOCATION}, PERMISSIONS_REQUEST_ACCESS_FINE_LOCATION);
                            }
                        });

                Dialog alertDialog = builder.create();
                alertDialog.setCanceledOnTouchOutside(false);
                alertDialog.show();
                return false;
            }
        }
        return true;
    }

    public static boolean requestLocationServicePermission(final Fragment fragment) {
        LocationManager locationManager = (LocationManager) fragment.getActivity().getSystemService(LOCATION_SERVICE);
        String permission_location_info = "Grant location access in the Android settings to search for Bluetooth devices. Optionally revoke it afterwards.";

        if (!(locationManager.isProviderEnabled(LocationManager.GPS_PROVIDER) || locationManager.isProviderEnabled(LocationManager.NETWORK_PROVIDER))) {

            AlertDialog.Builder builder = new AlertDialog.Builder(fragment.getContext());
            builder.setTitle("Information");
            builder.setMessage(permission_location_info);
            builder.setPositiveButton("OK", new DialogInterface.OnClickListener() {
                public void onClick(DialogInterface dialogInterface, int i) {
                    // Show location settings when the user acknowledges the alert dialog
                    Intent intent = new Intent(Settings.ACTION_LOCATION_SOURCE_SETTINGS);
                    fragment.getActivity().startActivity(intent);
                }
            });

            Dialog alertDialog = builder.create();
            alertDialog.setCanceledOnTouchOutside(false);
            alertDialog.show();
            return false;
        }
        return true;
    }
}
