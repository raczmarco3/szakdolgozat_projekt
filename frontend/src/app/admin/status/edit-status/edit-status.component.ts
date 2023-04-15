import {Component, Inject} from '@angular/core';
import {MAT_DIALOG_DATA, MatDialogRef} from "@angular/material/dialog";
import {Method} from "../../admin-model/method";
import {FormControl, FormGroup} from "@angular/forms";
import {StatusService} from "../../admin-service/status-service";

@Component({
  selector: 'app-edit-status',
  templateUrl: './edit-status.component.html',
  styleUrls: ['./edit-status.component.css']
})
export class EditStatusComponent {
  jsonContent: JSON;
  msg: string;
  obj: any;
  constructor(public dialogRef: MatDialogRef<EditStatusComponent>, private statusService: StatusService,
              @Inject(MAT_DIALOG_DATA) public data: Method) {}

  editStatusForm = new FormGroup({
    name: new FormControl(),
  });
  onClose() {
    this.dialogRef.close();
  }
  onSubmit(event: any) {
    if(event.submitter.name == "add") {
      this.obj = {
        "id": this.data.id,
        "name": this.editStatusForm.get('name')?.value,
      };

      this.jsonContent = <JSON>this.obj;

      this.statusService.editStatus(this.data.id, this.jsonContent).subscribe(
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
