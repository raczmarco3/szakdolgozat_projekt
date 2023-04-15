import { Component } from '@angular/core';
import {MatDialogRef} from "@angular/material/dialog";
import {FormControl, FormGroup} from "@angular/forms";
import {ProductService} from "../../admin-service/product-service";
import {CategoryService} from "../../admin-service/category-service";
import {Category} from "../../admin-model/category";
import {Observable, Subscriber} from "rxjs";

@Component({
  selector: 'app-add-product',
  templateUrl: './add-product.component.html',
  styleUrls: ['./add-product.component.css']
})
export class AddProductComponent {
  jsonContent: JSON;
  msg: string;
  obj: any;
  categories: Category[] = [];
  imageError: any = null;
  base64code!: any;

  constructor(public dialogRef: MatDialogRef<AddProductComponent>, private productService: ProductService,
              private categoryService: CategoryService) {}

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

  addProductForm = new FormGroup({
    name: new FormControl(),
    price: new FormControl(),
    category: new FormControl(),
  });
  onClose() {
    this.dialogRef.close();
  }
  onSubmit(event: any) {
    if(event.submitter.name == "add") {
      this.obj = {
        "name": this.addProductForm.get('name')?.value,
        "price": this.addProductForm.get('price')?.value,
        "categoryId": this.addProductForm.get('category')?.value,
        "imgData": this.base64code,
      };
      console.log(this.addProductForm);

      this.jsonContent = <JSON>this.obj;
      console.log(this.jsonContent);

      this.productService.addProduct(this.jsonContent).subscribe(
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
