import { Component } from '@angular/core';
import {RegistrationService} from "../service/registration-service";
import {FormControl, FormGroup} from "@angular/forms";
import {MatDialogRef} from "@angular/material/dialog";

@Component({
  selector: 'app-registration',
  templateUrl: './registration.component.html',
  styleUrls: ['./registration.component.css']
})
export class RegistrationComponent {
  jsonContent: JSON;
  msg: string;
  obj: any;

  constructor(public dialogRef: MatDialogRef<RegistrationComponent>, private registrationService: RegistrationService) {}

  registerForm = new FormGroup({
    username: new FormControl(),
    password:new FormControl(),
  });

  onSubmit(event: any) {
    if(event.submitter.name == "register") {
      this.obj = {
        "username": this.registerForm.get('username')?.value,
        "password": this.registerForm.get('password')?.value
      };

      this.jsonContent = <JSON>this.obj;
      this.registrationService.register(this.jsonContent).subscribe(
        {
          next: () => {
            this.msg = "A regisztráció sikeres!";
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
