import { Component } from '@angular/core';
import {RegistrationComponent} from "../registration/registration.component";
import {MatDialog} from '@angular/material/dialog';
import {LoginComponent} from "../login/login.component";

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
  constructor(public dialog: MatDialog) { }
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

}
