import { Component } from '@angular/core';
import {MatDialogRef} from "@angular/material/dialog";
import {FormControl, FormGroup} from "@angular/forms";
import {UserService} from "../service/user-service";
import {LoginService} from "../service/login-service";
import {Router} from "@angular/router"
import {CartService} from "../service/cart-service";

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent {
  jsonContent: JSON;
  msg: string;
  obj: any;

  constructor(public dialogRef: MatDialogRef<LoginComponent>, public userService: UserService,
              private loginService: LoginService, private router: Router, private cartService: CartService) {
  }

  loginForm = new FormGroup({
    username: new FormControl(),
    password: new FormControl(),
  });

  onSubmit(event: any) {
    if (event.submitter.name == "login") {
      this.obj = {
        "username": this.loginForm.get('username')?.value,
        "password": this.loginForm.get('password')?.value
      };

      this.jsonContent = <JSON>this.obj;

      this.userService.login(this.jsonContent).subscribe(
        {
          next: (response) => {
            this.loginService.saveData("username", response.username);
            this.loginService.saveData("role", response.role);
            this.loginService.saveData("loggedIn", 'true');
            window.location.reload();
          },
          error: (msg) => {
            this.msg = "Hibás felhasználónév vagy jelszó!";
          }
        }
      );
    }
  }

  onClose() {
    this.dialogRef.close();
  }
}
