import { Component } from '@angular/core';
import {RegistrationService} from "../service/registration-service";
import {FormControl, FormGroup} from "@angular/forms";

@Component({
  selector: 'app-registration',
  templateUrl: './registration.component.html',
  styleUrls: ['./registration.component.css']
})
export class RegistrationComponent {
  jsonContent: JSON;
  msg: string;

  constructor(private registrationService: RegistrationService) {}

  registerForm = new FormGroup({
    username: new FormControl(),
    password:new FormControl(),
  });

  onSubmit(event: any) {

  }

}
