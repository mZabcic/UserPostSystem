import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Router } from '@angular/router';
import { AppConfig } from '../app.config';
import {AuthService } from './auth.service';


@Injectable()
export class UserService {

  constructor(private http: HttpClient, public router: Router, private authService : AuthService) { 
  }
  
  private url = AppConfig.API_ENDPOINT;

  public me()  : Observable<any> {
    return this.http.get<any>(this.url + 'users/me' ).pipe();
  }

  public users()  : Observable<any> {
    return this.http.get<any>(this.url + 'users' ).pipe();
  }

  public follow(id)  : Observable<any> {
    return this.http.post<any>(this.url + 'users/follow/' + id, {} ).pipe();
  }

  public unFollow(id)  : Observable<any> {
    return this.http.delete<any>(this.url + 'users/unfollow/' + id ).pipe();
  }


}