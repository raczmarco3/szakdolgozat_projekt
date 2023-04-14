import { Component } from '@angular/core';
import {MatDialogRef} from "@angular/material/dialog";
import {FormControl, FormGroup} from "@angular/forms";
import {UserService} from "../service/user-service";

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent {
  jsonContent: JSON;
  msg: string;
  obj: any;

  constructor(public dialogRef: MatDialogRef<LoginComponent>, public userService: UserService) {}

  loginForm = new FormGroup({
    username: new FormControl(),
    password:new FormControl(),
  });

  onSubmit(event: any) {
    if(event.submitter.name == "login") {
      this.obj = {
        "username": this.loginForm.get('username')?.value,
        "password": this.loginForm.get('password')?.value
      };

      this.jsonContent = <JSON>this.obj;

      this.userService.login(this.jsonContent).subscribe(
        {
          next: () => {
            this.dialogRef.close();
          },
          error: (msg) => {
            this.msg = msg.error.msg;
          }
        }
      );

    }
  }

  onClose() {
    this.dialogRef.close();
  }

}
