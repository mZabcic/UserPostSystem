import { NgModule }             from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { HomepageComponent }             from './homepage/homepage.component';
import { LoginComponent }             from './login/login.component';
import { AuthGuard } from './guards/auth.guard';
import { GuestGuard } from './guards/guest.guard';
import { RegisterComponent } from './register/register.component';
import { FriendsComponent } from './friends/friends.component';
import { PostComponent } from './post/post.component';


const routes: Routes = [
  { path: '', component: HomepageComponent, canActivate: [AuthGuard] },
  { path: 'login', component: LoginComponent, canActivate: [GuestGuard] },
  { path: 'register', component: RegisterComponent, canActivate: [GuestGuard] },
  { path: 'friends', component: FriendsComponent, canActivate: [AuthGuard] },
  { path: 'post', component: PostComponent, canActivate: [AuthGuard] },
];

@NgModule({
  imports: [ RouterModule.forRoot(routes) ],
  exports: [ RouterModule ]
})
export class AppRoutingModule {}