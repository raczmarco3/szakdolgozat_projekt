import { Component } from '@angular/core';
import {RegistrationComponent} from "../registration/registration.component";
import {MatDialog} from '@angular/material/dialog';
import {LoginComponent} from "../login/login.component";
import {LoginService} from "../service/login-service";
import {UserService} from "../service/user-service";

@Component({
  selector: 'app-menu',
  templateUrl: './menu.component.html',
  styleUrls: ['./menu.component.css']
})
export class MenuComponent {

  registerOpened: boolean = false;
  loginOpened: boolean = false;
  registerDialog: any;
  loginDialog: any;
  loggedIn: boolean = false;
  isAdmin: boolean = false;

  constructor(public dialog: MatDialog, private loginService: LoginService, private userService: UserService) { }

  ngOnInit() {
    if(this.loginService.getData("loggedIn") == "true") {
      this.loggedIn = true;
    }

    if(this.loginService.getData("role") == "ROLE_ADMIN") {
      this.isAdmin = true;
    }
  }
  register(opened: boolean) {
    if(this.loginOpened) {
      this.loginDialog.close();
    }
    if(!this.registerOpened) {
      this.registerOpened = true;

      this.registerDialog = this.dialog.open(RegistrationComponent,
        {height: '270px', width: '600px'});
      this.registerDialog.afterClosed().subscribe(
        {
          next: () =>
          {
            this.registerOpened = false;
          }
        }
      );
    }
  }

  login(opened: boolean) {
    if(this.registerOpened) {
      this.registerDialog.close();
    }

    if(!this.loginOpened) {
      this.loginOpened = true;

      this.loginDialog = this.dialog.open(LoginComponent,
        {height: '270px', width: '600px'});
      this.loginDialog.afterClosed().subscribe(
        {
          next: () =>
          {
            this.loginOpened = false;
          }
        }
      );
    }
  }
  logout() {
    this.userService.logout().subscribe(
      {
        next: (response) => {
          this.loginService.removeData("username");
          this.loginService.removeData("role");
          this.loginService.removeData("loggedIn");
          window.open("/");
        }
      });
  }

}
