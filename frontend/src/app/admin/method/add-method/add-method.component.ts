import { Component } from '@angular/core';
import {MatDialogRef} from "@angular/material/dialog";
import {FormControl, FormGroup} from "@angular/forms";
import {MethodService} from "../../admin-service/method-service";

@Component({
  selector: 'app-add-method',
  templateUrl: './add-method.component.html',
  styleUrls: ['./add-method.component.css']
})
export class AddMethodComponent {
  jsonContent: JSON;
  msg: string;
  obj: any;

  constructor(public dialogRef: MatDialogRef<AddMethodComponent>, private methodService: MethodService) {}

  addMethodForm = new FormGroup({
    name: new FormControl(),
  });
  onClose() {
    this.dialogRef.close();
  }
  onSubmit(event: any) {
    if(event.submitter.name == "add") {
      this.obj = {
        "name": this.addMethodForm.get('name')?.value,
      };

      this.jsonContent = <JSON>this.obj;

      this.methodService.addMethod(this.jsonContent).subscribe(
        {
          next: (response) => {
            this.dialogRef.close();
          },
          error: (msg) => {
            this.msg = msg.error.msg;
          }
        }
      );
    }
  }
}
