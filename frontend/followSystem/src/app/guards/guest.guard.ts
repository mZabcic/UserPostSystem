import { Injectable } from '@angular/core';
import { Router, CanActivate } from '@angular/router';
import { AuthService } from '../services/auth.service';
@Injectable()
export class GuestGuard implements CanActivate {
    constructor(public auth: AuthService, public router: Router) { }
    canActivate(): boolean {
        if (this.auth.isAuthenticated()) {
            this.router.navigate(['/']);
            return false;
        }
        return true;
    }
}