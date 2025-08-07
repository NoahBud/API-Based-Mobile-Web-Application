package com.example.projet.activity;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.os.Handler;
import android.view.View;
import android.view.animation.Animation;
import android.view.animation.AnimationUtils;
import android.widget.TextView;

import com.example.projet.R;

public class SplashActivity extends Activity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_splash);
        View background = findViewById(R.id.splashBackground);
        Animation bgAnimation = AnimationUtils.loadAnimation(this, R.anim.background_animation);
        background.startAnimation(bgAnimation);
        TextView letterB = findViewById(R.id.letterB);
        TextView letterO = findViewById(R.id.letterO);
        TextView letterO2 = findViewById(R.id.letterO2);
        TextView letterK = findViewById(R.id.letterK);
        TextView letterApostrophe = findViewById(R.id.letterApostrophe);
        TextView letterI = findViewById(R.id.letterI);
        TextView letterN = findViewById(R.id.letterN);
        Animation fadeIn = AnimationUtils.loadAnimation(this, R.anim.fade_in);
        letterB.startAnimation(fadeIn);
        new Handler().postDelayed(() -> letterO.startAnimation(fadeIn), 200);
        new Handler().postDelayed(() -> letterO2.startAnimation(fadeIn), 400);
        new Handler().postDelayed(() -> letterK.startAnimation(fadeIn), 600);
        new Handler().postDelayed(() -> letterApostrophe.startAnimation(fadeIn), 800);
        new Handler().postDelayed(() -> letterI.startAnimation(fadeIn), 1000);
        new Handler().postDelayed(() -> letterN.startAnimation(fadeIn), 1200);
        new Handler().postDelayed(() -> {
            startActivity(new Intent(SplashActivity.this, MainActivity.class));
            finish();
        }, 3000);
    }
}
