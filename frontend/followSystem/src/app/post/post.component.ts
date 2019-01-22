import { Component, OnInit } from '@angular/core';
import { NgxUiLoaderService } from 'ngx-ui-loader';
import { Router } from '@angular/router';
import { FormGroup, FormControl } from '@angular/forms';
import { Validators } from '@angular/forms';
import { PostService } from '../services/post.service';

@Component({
  selector: 'app-post',
  templateUrl: './post.component.html',
  styleUrls: ['./post.component.css']
})
export class PostComponent implements OnInit {
  error: string = "";

  me : any;
  
  post: FormGroup = new FormGroup({
    title: new FormControl('', [Validators.required]),
    content: new FormControl('', [Validators.required]),
  });;

  constructor(private ngxService: NgxUiLoaderService, public router: Router, private postService: PostService) {
  }

  ngOnInit() {
  }

  onSubmit() {
    this.ngxService.start();
    this.postService.create(this.post.value).subscribe((data) => {
      this.router.navigate(['']);
      this.ngxService.stop();
    }, (err) => {
      this.error = "Check your form data";
      this.ngxService.stop();
    })

  }

}
