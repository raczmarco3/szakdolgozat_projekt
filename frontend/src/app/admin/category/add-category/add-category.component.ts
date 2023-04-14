import { Component } from '@angular/core';
import {MatDialogRef} from "@angular/material/dialog";
import {CategoryService} from "../../admin-service/category-service";
import {FormControl, FormGroup} from "@angular/forms";

@Component({
  selector: 'app-add-category',
  templateUrl: './add-category.component.html',
  styleUrls: ['./add-category.component.css']
})
export class AddCategoryComponent {
  jsonContent: JSON;
  msg: string;
  obj: any;

  constructor(public dialogRef: MatDialogRef<AddCategoryComponent>, private categoryService: CategoryService) {}

  addCategoryForm = new FormGroup({
    name: new FormControl(),
  });

  onClose() {
    this.dialogRef.close();
  }

  onSubmit(event: any) {
    if(event.submitter.name == "add") {
      this.obj = {
        "name": this.addCategoryForm.get('name')?.value,
      };

      this.jsonContent = <JSON>this.obj;

      this.categoryService.addCategory(this.jsonContent).subscribe(
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
