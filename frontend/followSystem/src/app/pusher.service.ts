import { Injectable } from '@angular/core';
import { AppConfig } from './app.config';

declare const Pusher: any;

import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class PusherService {
  pusher: any;
    channel: any;
    
    constructor(private http: HttpClient) {
      let me = JSON.parse(localStorage.getItem('user'));
      this.pusher = new Pusher('ebe1aedff693154fa145', {
        cluster: 'eu',
        authEndpoint: AppConfig.API_ENDPOINT + 'broadcasting/auth',
        auth: {
          headers: {
              Authorization: 'Bearer ' + localStorage.getItem('access_token')
          },
      }
      });

        this.channel = this.pusher.subscribe('private-user-' + me.id);
    }

      
}
