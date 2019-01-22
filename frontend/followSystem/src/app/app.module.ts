import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { HttpClientModule }    from '@angular/common/http';
import { AppRoutingModule } from './app-routing.module';

import { AppComponent } from './app.component';
import { HomepageComponent } from './homepage/homepage.component';
import { LoginComponent } from './login/login.component';
import { AuthService } from './services/auth.service';
import { UserService } from './services/user.service';
import { PostService } from './services/post.service';
import { AuthGuard } from './guards/auth.guard';
import { GuestGuard } from './guards/guest.guard';
import { JwtModule } from '@auth0/angular-jwt';
import { HeaderComponent } from './header/header.component';
import { NgxUiLoaderModule } from  'ngx-ui-loader';
import { ReactiveFormsModule } from '@angular/forms';
import { RegisterComponent } from './register/register.component';
import { FriendsComponent } from './friends/friends.component';
import { HTTP_INTERCEPTORS } from '@angular/common/http';
import { AuthInterceptor } from './auth.interceptor';
import { PostComponent } from './post/post.component';

export function tokenGetter() {
  return localStorage.getItem('access_token');
}

@NgModule({
  declarations: [
    AppComponent,
    HomepageComponent,
    LoginComponent,
    HeaderComponent,
    RegisterComponent,
    FriendsComponent,
    PostComponent
  ],
  imports: [
    BrowserModule,
    HttpClientModule,
    AppRoutingModule,
    FormsModule,
    JwtModule.forRoot({
      config: {
        tokenGetter: tokenGetter
      }
    }),
    NgxUiLoaderModule,
    ReactiveFormsModule
  ],
  providers: [AuthService, AuthGuard, GuestGuard, UserService, {
    provide : HTTP_INTERCEPTORS,
    useClass: AuthInterceptor,
    multi   : true,
  },PostService],
  bootstrap: [AppComponent]
})
export class AppModule { }
