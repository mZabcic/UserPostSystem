import { Component, OnInit } from '@angular/core';
import { UserService } from '../services/user.service';
import { AuthService } from '../services/auth.service';
import { NgxUiLoaderService } from 'ngx-ui-loader';

@Component({
  selector: 'app-friends',
  templateUrl: './friends.component.html',
  styleUrls: ['./friends.component.css']
})
export class FriendsComponent implements OnInit {
  myFriends : Array<number>;
  me : any;
  users : any[];
  error : boolean = false;

  constructor(private userService : UserService, private authService : AuthService, private ngxService: NgxUiLoaderService) { }

  ngOnInit() {
    this.ngxService.start();
    this.userService.me().subscribe((data) => {
      this.myFriends = new Array<number>();
      this.me = data;
      for (let d of data.following) {
        this.myFriends.push(d.id);
      }
      this.getAllUsers();
    }, (err) => {
      this.authService.logout();
      this.ngxService.stop();
    })
  }


  getAllUsers() : void {
    this.userService.users().subscribe((data) => {
      this.users = data;
      this.ngxService.stop();
    }, (err) => {
      this.authService.logout();
      this.ngxService.stop();
    })
  }

  isFriend(id) : boolean {
    return !this.myFriends.includes(id);
  }

  follow(id) : void {
    this.userService.follow(id).subscribe((data) => {
      this.myFriends.push(id);
    }, (err) => {
      this.error = true;
    })
  }

  unfollow(id) : void {
    this.userService.unFollow(id).subscribe((data) => {
      const index = this.myFriends.indexOf(id, 0);
      if (index > -1) {
        this.myFriends.splice(index, 1);
      }
    }, (err) => {
      this.error = true;
    })
  }

}
