import {Component, Inject} from '@angular/core';
import {MAT_DIALOG_DATA, MatDialogRef} from "@angular/material/dialog";
import {Category} from "../../admin-model/category";
import {CategoryService} from "../../admin-service/category-service";
import {FormControl, FormGroup} from "@angular/forms";

@Component({
  selector: 'app-edit-category',
  templateUrl: './edit-category.component.html',
  styleUrls: ['./edit-category.component.css']
})
export class EditCategoryComponent {
  jsonContent: JSON;
  msg: string;
  obj: any;
  constructor(public dialogRef: MatDialogRef<EditCategoryComponent>, private categoryService: CategoryService,
              @Inject(MAT_DIALOG_DATA) public data: Category) {}

  editCategoryForm = new FormGroup({
    name: new FormControl(),
  });

  onClose() {
    this.dialogRef.close();
  }

  onSubmit(event: any) {
    if(event.submitter.name == "add") {
      this.obj = {
        "id": this.data.id,
        "name": this.editCategoryForm.get('name')?.value,
      };

      this.jsonContent = <JSON>this.obj;

      this.categoryService.editCategory(this.data.id, this.jsonContent).subscribe(
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
