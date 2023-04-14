import { Component } from '@angular/core';
import {LoginService} from "../../service/login-service";

@Component({
  selector: 'app-index',
  templateUrl: './index.component.html',
  styleUrls: ['./index.component.css']
})
export class IndexComponent {
  loggedIn: boolean = false;
  isAdmin: boolean = false;
  constructor(private loginService: LoginService) { }

  ngOnInit() {
    if(this.loginService.getData("loggedIn") == "true") {
      this.loggedIn = true;
    }

    if(this.loginService.getData("role") == "ROLE_ADMIN") {
      this.isAdmin = true;
    }
  }
}
