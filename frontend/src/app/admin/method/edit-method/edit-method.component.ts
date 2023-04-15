import {Component, Inject} from '@angular/core';
import {MAT_DIALOG_DATA, MatDialogRef} from "@angular/material/dialog";
import {FormControl, FormGroup} from "@angular/forms";
import {MethodService} from "../../admin-service/method-service";
import {Method} from "../../admin-model/method";

@Component({
  selector: 'app-edit-method',
  templateUrl: './edit-method.component.html',
  styleUrls: ['./edit-method.component.css']
})
export class EditMethodComponent {
  jsonContent: JSON;
  msg: string;
  obj: any;
  constructor(public dialogRef: MatDialogRef<EditMethodComponent>, private methodService: MethodService,
              @Inject(MAT_DIALOG_DATA) public data: Method) {}

  editMethodForm = new FormGroup({
    name: new FormControl(),
  });
  onClose() {
    this.dialogRef.close();
  }
  onSubmit(event: any) {
    if(event.submitter.name == "add") {
      this.obj = {
        "id": this.data.id,
        "name": this.editMethodForm.get('name')?.value,
      };

      this.jsonContent = <JSON>this.obj;

      this.methodService.editMethod(this.data.id, this.jsonContent).subscribe(
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
