import { Component, OnInit } from '@angular/core';
import { AuthService } from '../services/auth.service';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.css']
})
export class HeaderComponent implements OnInit {

  user : any;

  constructor(private authService : AuthService) { }

  ngOnInit() {
      this.user = JSON.parse(localStorage.getItem('user'));
  }

  logout() : void {
    this.authService.logout();
  }

}
