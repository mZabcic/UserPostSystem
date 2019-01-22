import { Component, OnInit } from '@angular/core';
import { NgxUiLoaderService } from 'ngx-ui-loader';
import { Router } from '@angular/router';
import { FormGroup, FormControl } from '@angular/forms';
import { Validators } from '@angular/forms';
import { AuthService } from '../services/auth.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {
  error : string = "";

  login : FormGroup = new FormGroup({
    email: new FormControl('',  [Validators.required]),
    password: new FormControl(''),
  });;

  constructor(private ngxService: NgxUiLoaderService, public router: Router, private authService : AuthService) {
   }

  ngOnInit() {
    
  }

  onSubmit() {
    this.ngxService.start();
    this.authService.login(this.login.value).subscribe((data) => {
       localStorage.setItem('access_token', data.token);
       localStorage.setItem("user", JSON.stringify(data.user));
       this.router.navigate( ['']);
       this.ngxService.stop();
    }, (err) => {
      this.error = "Check your credentials";
      this.ngxService.stop();
    })
   
  }

}
