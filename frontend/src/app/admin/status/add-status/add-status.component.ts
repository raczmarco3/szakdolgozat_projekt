import { Component } from '@angular/core';
import {MatDialogRef} from "@angular/material/dialog";
import {FormControl, FormGroup} from "@angular/forms";
import {StatusService} from "../../admin-service/status-service";

@Component({
  selector: 'app-add-status',
  templateUrl: './add-status.component.html',
  styleUrls: ['./add-status.component.css']
})
export class AddStatusComponent {
  jsonContent: JSON;
  msg: string;
  obj: any;

  constructor(public dialogRef: MatDialogRef<AddStatusComponent>, private statusService: StatusService) {}

  addStatusForm = new FormGroup({
    name: new FormControl(),
  });
  onClose() {
    this.dialogRef.close();
  }
  onSubmit(event: any) {
    if(event.submitter.name == "add") {
      this.obj = {
        "name": this.addStatusForm.get('name')?.value,
      };

      this.jsonContent = <JSON>this.obj;

      this.statusService.addStatus(this.jsonContent).subscribe(
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
