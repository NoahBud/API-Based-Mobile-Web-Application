package com.example.projet.network;

import com.example.projet.adapter.BookResponseWrapper;
import com.example.projet.models.Copy;
import com.example.projet.network.request.AddBookRequest;
import com.example.projet.models.User;
import com.example.projet.models.Author;
import com.example.projet.models.Book;
import com.example.projet.models.Box;
import com.example.projet.models.Editor;
import com.example.projet.network.request.AddBoxRequest;
import com.example.projet.network.request.BoxIdRequest;
import com.example.projet.network.request.response.AuthorResponse;
import com.example.projet.network.request.response.BookResponse;
import com.example.projet.network.request.response.BoxResponse;
import com.example.projet.network.request.response.CopyResponse;
import com.example.projet.network.request.response.EditorResponse;
import com.example.projet.network.request.LoginRequest;
import com.example.projet.network.request.RegisterRequest;
import com.example.projet.network.request.response.AuthResponse;
import com.example.projet.network.request.response.IdResponse;

import retrofit2.Call;
import retrofit2.http.Body;
import retrofit2.http.GET;
import retrofit2.http.POST;
import retrofit2.http.PUT;
import retrofit2.http.Path;
import retrofit2.http.Query;

import java.util.List;

public interface ApiService {

    @GET("users")
    Call<List<User>> getUsers();

    @POST("login")
    Call<AuthResponse> loginUser(@Body LoginRequest loginRequest);

    @POST("register")
    Call<AuthResponse> registerUser(@Body RegisterRequest registerRequest);


    @POST("books")
    Call<IdResponse> addBook(@Body AddBookRequest bookRequest);

    @GET("editors")
    Call<List<Editor>> getAllEditors();

    @GET("authors")
    Call<List<Author>> getAllAuthors();

    @GET("boxes")
    Call<List<Box>> getAllBoxes();

    @POST("boxes")
    Call<BoxResponse> addBox(@Body AddBoxRequest boxRequest);

    @GET("books/{id}")
    Call<BookResponse> getBookById(@Path("id") int bookId);

    @GET("books/authors/{book}")
    Call<AuthorResponse> getAuthorsByBook(@Path("book") int bookId);

    @GET("editors/{id}")
    Call<EditorResponse> getEditorById(@Path("id") int editorId);

    @GET("boxes/{box}/copies")
    Call<CopyResponse> getCopies(@Path("box") int boxId);

    @POST("books/{bookId}/copies")
    Call<Copy> createCopyForBook(@Path("bookId") int bookId, @Body BoxIdRequest boxIdRequest);

    @GET("books/isbn/{isbn}")
    Call<BookResponseWrapper> getBookByISBN(@Path("isbn") String isbn);


    @POST("boxes/{boxId}/copies")
    Call<Void> addBookToBox(@Path("boxId") int boxId, @Body Book book);

    @GET("books/copies/{book}")
    Call<CopyResponse> getCopiesByBook(@Path("book") int bookId);


    @PUT("copies/{copy}/disponibilite")
    Call<Void> setCopyDisponibility(@Path("copy") int copyId);








}
