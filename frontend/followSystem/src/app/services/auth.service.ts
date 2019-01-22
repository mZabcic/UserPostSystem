import { Injectable } from '@angular/core';
import { JwtHelperService } from '@auth0/angular-jwt';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable, Subject } from 'rxjs';
import { Router } from '@angular/router';
import { AppConfig } from '../app.config';


@Injectable()
export class AuthService {
  constructor(public jwtHelper: JwtHelperService, private http: HttpClient, public router: Router) { }

  private url = AppConfig.API_ENDPOINT;

  public isAuthenticated(): boolean {
    const token = localStorage.getItem('access_token');
    return !this.jwtHelper.isTokenExpired(token);
  }

  public login(data) : Observable<any> {
    let headers: HttpHeaders = new HttpHeaders();
    headers.append('Access-Control-Allow-Methods','GET,PUT,POST,DELETE');
    headers.append('Access-Control-Allow-Headers','Content-Type');
    return this.http.post<any>(this.url + 'login', data , { headers }).pipe();
  }

  public register(data) : Observable<any> {
    let headers: HttpHeaders = new HttpHeaders();
    headers.append('Access-Control-Allow-Methods','GET,PUT,POST,DELETE');
    headers.append('Access-Control-Allow-Headers','Content-Type');
    return this.http.post<any>(this.url + 'register', data , { headers }).pipe();
  }

  public logout() : void {
    localStorage.removeItem("access_token");
    localStorage.removeItem("user");
    this.router.navigate(['login']);
  }

  public getToken() : string {
    const token = localStorage.getItem('access_token');
    return token;
  }

}