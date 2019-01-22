import { Component, OnInit } from '@angular/core';
import { NgxUiLoaderService } from 'ngx-ui-loader';
import { Router } from '@angular/router';
import { FormGroup, FormControl } from '@angular/forms';
import { Validators } from '@angular/forms';
import { AuthService } from '../services/auth.service';

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.css']
})
export class RegisterComponent implements OnInit {
  error: string = "";

  login: FormGroup = new FormGroup({
    email: new FormControl('', [Validators.required]),
    password: new FormControl('', [Validators.required]),
    first_name: new FormControl('', [Validators.required]),
    last_name: new FormControl('', [Validators.required])
  });;

  constructor(private ngxService: NgxUiLoaderService, public router: Router, private authService: AuthService) {
  }

  ngOnInit() {
    this.login.patchValue({
      email: ''
    });
  }

  onSubmit() {
    this.ngxService.start();
    this.authService.register(this.login.value).subscribe((data) => {
      localStorage.setItem('access_token', data.token);
      localStorage.setItem("user", JSON.stringify(data.user));
      this.router.navigate(['']);
      this.ngxService.stop();
    }, (err) => {
      this.error = "Check your form data";
      this.ngxService.stop();
    })

  }

}
