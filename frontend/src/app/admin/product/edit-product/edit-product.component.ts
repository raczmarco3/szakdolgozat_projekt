import {Component, Inject} from '@angular/core';
import {Category} from "../../admin-model/category";
import {MAT_DIALOG_DATA, MatDialogRef} from "@angular/material/dialog";
import {ProductService} from "../../admin-service/product-service";
import {CategoryService} from "../../admin-service/category-service";
import {FormControl, FormGroup} from "@angular/forms";
import {Observable, Subscriber} from "rxjs";
import {Product} from "../../admin-model/product";

@Component({
  selector: 'app-edit-product',
  templateUrl: './edit-product.component.html',
  styleUrls: ['./edit-product.component.css']
})
export class EditProductComponent {
  jsonContent: JSON;
  msg: string;
  obj: any;
  categories: Category[] = [];
  imageError: any = null;
  base64code!: any;

  constructor(public dialogRef: MatDialogRef<EditProductComponent>, private productService: ProductService,
              private categoryService: CategoryService, @Inject(MAT_DIALOG_DATA) public data: Product) {}

  ngOnInit() {
    this.getCategories();
  }
  getCategories() {
    this.categoryService.getCategories().subscribe(
      {
        next: (response) => {
          this.categories = response;
        },
        error: (msg) => {
          this.msg = msg.error.msg;
        }
      }
    );
  }

  editProductForm = new FormGroup({
    name: new FormControl(),
    price: new FormControl(),
    category: new FormControl(),
  });
  onClose() {
    this.dialogRef.close();
  }
  onSubmit(event: any) {
    if(event.submitter.name == "add") {
      if(this.base64code == undefined) {
        this.base64code = this.data.imageData;
      }
      this.obj = {
        "id": this.data.id,
        "name": this.editProductForm.get('name')?.value,
        "price": this.editProductForm.get('price')?.value,
        "categoryId": this.editProductForm.get('category')?.value,
        "imgData": this.base64code,
      };

      this.jsonContent = <JSON>this.obj;

      this.productService.editProduct(this.data.id, this.jsonContent).subscribe(
        {
          next: (response) => {
            this.dialogRef.close();
          },
          error: (msg) => {
            this.msg = msg.error.msg;
            console.log(msg);
          }
        }
      );
    }
  }
  fileChangeEvent = ($event: Event) => {
    const target = $event.target as HTMLInputElement;
    const file: File = (target.files as FileList)[0];
    this.convertToBase64(file);
  };
  convertToBase64(file: File) {
    const observable = new Observable((subscriber: Subscriber<any>) => {
      this.readFile(file, subscriber);
    });
    observable.subscribe((d) => {
      this.base64code = d
    })
  }
  readFile(file: File, subscriber: Subscriber<any>) {
    const filereader = new FileReader();
    filereader.readAsDataURL(file);
    filereader.onload = () => {
      subscriber.next(filereader.result);
      subscriber.complete();
    };
    filereader.onerror = (error) => {
      subscriber.error(error);
      subscriber.complete();
    };
  }
}
