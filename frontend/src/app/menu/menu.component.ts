import { Component } from '@angular/core';
import {RegistrationComponent} from "../registration/registration.component";
import {MatDialog} from '@angular/material/dialog';

@Component({
  selector: 'app-menu',
  templateUrl: './menu.component.html',
  styleUrls: ['./menu.component.css']
})
export class MenuComponent {

  registerOpened: boolean = false;
  constructor(public dialog: MatDialog) { }
  register(opened: boolean) {
    if(!this.registerOpened) {
      this.registerOpened = true;

      const dialogRef = this.dialog.open(RegistrationComponent,
        {height: '270px', width: '600px'});
      dialogRef.afterClosed().subscribe(
        {
          next: () =>
          {
            this.registerOpened = false;
          }
        }
      );
    }
  }

}
