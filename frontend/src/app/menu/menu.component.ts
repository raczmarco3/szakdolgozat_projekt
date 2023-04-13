import { Component } from '@angular/core';
import {RegistrationComponent} from "../registration/registration.component";
import {MatDialog} from '@angular/material/dialog';

@Component({
  selector: 'app-menu',
  templateUrl: './menu.component.html',
  styleUrls: ['./menu.component.css']
})
export class MenuComponent {

  constructor(public dialog: MatDialog) { }
  register() {
    const dialogRef = this.dialog.open(RegistrationComponent, {height: '350px', width: '600px'});

  }

}
