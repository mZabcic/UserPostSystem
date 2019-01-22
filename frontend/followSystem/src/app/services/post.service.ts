import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Router } from '@angular/router';
import { AppConfig } from '../app.config';
import {AuthService } from './auth.service';


@Injectable()
export class PostService {

  constructor(private http: HttpClient, public router: Router, private authService : AuthService) { 
  }
  
  private url = AppConfig.API_ENDPOINT;

 

  public create(data)  : Observable<any> {
    return this.http.post<any>(this.url + 'posts', data ).pipe();
  }

  public get()  : Observable<any> {
    return this.http.get<any>(this.url + 'posts/following' ).pipe();
  }




}