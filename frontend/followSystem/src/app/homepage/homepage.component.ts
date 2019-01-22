import { Component, OnInit } from '@angular/core';
import { NgxUiLoaderService } from 'ngx-ui-loader';
import { Router } from '@angular/router';
import { PostService } from '../services/post.service';
import { PusherService } from '../pusher.service'
import { AppConfig } from '../app.config';

declare const Pusher: any;

@Component({
  selector: 'app-homepage',
  templateUrl: './homepage.component.html',
  styleUrls: ['./homepage.component.css']
})
export class HomepageComponent implements OnInit {
  user : any;
  error : boolean = false;
  posts : Array<any>;

  constructor(private ngxService: NgxUiLoaderService, public router: Router, private postService: PostService, private pusherService : PusherService) { }

  ngOnInit() {
    let me = JSON.parse(localStorage.getItem('user'));
    var pusher = new Pusher('ebe1aedff693154fa145', {
      cluster: 'eu',
      authEndpoint: AppConfig.API_ENDPOINT + 'broadcasting/auth',
      auth: {
        headers: {
            Authorization: 'Bearer ' + localStorage.getItem('access_token')
        },
    }
    });
    var channel = pusher.subscribe('private-user-' + me.id);
      channel.bind('post-created', (data) => {
        data.post.new = true;
        this.posts.unshift(data.post);
      })

    this.ngxService.start();
    this.postService.get().subscribe((data) => {
      this.posts = data;
     
      
      this.ngxService.stop();
    }, (err) => {
      this.error = true;
      this.ngxService.stop();
    })
  }

  mouse(post) {
    if (post.new) {
      post.new = false;
    }
  }

}
