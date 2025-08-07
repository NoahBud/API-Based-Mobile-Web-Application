package com.example.projet.network;

import android.content.Context;
import android.content.SharedPreferences;

import okhttp3.OkHttpClient;
import okhttp3.Request;
import okhttp3.logging.HttpLoggingInterceptor;
import retrofit2.Retrofit;
import retrofit2.converter.gson.GsonConverterFactory;

public class ApiClient {
    private static Retrofit retrofit = null;

    public static Retrofit getClient(Context context) {
        SharedPreferences sharedPreferences = context.getSharedPreferences("MyPrefs", Context.MODE_PRIVATE);
        String token = sharedPreferences.getString("token", "");

        // ⚠️ Changer l'URL en fonction de l'environnement
        String BASE_URL = "http://10.0.2.2:8000/api/"; // Pour émulateur Android
        // String BASE_URL = "http://192.168.x.x:8000/api/"; // Pour téléphone physique

        OkHttpClient.Builder clientBuilder = new OkHttpClient.Builder();

        // 🔐 Ajout d'un interceptor pour le token JWT
        clientBuilder.addInterceptor(chain -> {
            Request originalRequest = chain.request();
            Request.Builder requestBuilder = originalRequest.newBuilder()
                    .header("Authorization", "Bearer " + token)
                    .method(originalRequest.method(), originalRequest.body());
            return chain.proceed(requestBuilder.build());
        });

        // 📝 Ajout d'un logger pour voir les requêtes HTTP en log
        HttpLoggingInterceptor loggingInterceptor = new HttpLoggingInterceptor();
        loggingInterceptor.setLevel(HttpLoggingInterceptor.Level.BODY);
        clientBuilder.addInterceptor(loggingInterceptor);

        // 🔄 Réinitialisation de Retrofit si nécessaire
        if (retrofit == null) {
            retrofit = new Retrofit.Builder()
                    .baseUrl(BASE_URL)
                    .client(clientBuilder.build()) // Utilise le client avec token + logs
                    .addConverterFactory(GsonConverterFactory.create())
                    .build();
        }
        return retrofit;
    }

    // 🚀 Fonction pour réinitialiser Retrofit (ex: après connexion/déconnexion)
    public static void resetClient() {
        retrofit = null;
    }
}
