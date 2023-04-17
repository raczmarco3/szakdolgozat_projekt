import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import {MainPageComponent} from "./main-page/main-page.component";
import {RegistrationComponent} from "./registration/registration.component";
import {ListCategoryComponent} from "./admin/category/list-category/list-category.component";
import {ListMethodComponent} from "./admin/method/list-method/list-method.component";
import {ListStatusComponent} from "./admin/status/list-status/list-status.component";
import {ListProductComponent} from "./admin/product/list-product/list-product.component";
import {ProductComponent} from "./product/product.component";
import {CategoryComponent} from "./category/category.component";
import {CartComponent} from "./cart/cart.component";
import {OrderComponent} from "./order/order.component";

const routes: Routes = [
  { path: '', component: MainPageComponent },
  { path: "register", component: RegistrationComponent },
  { path: "admin", component: ListProductComponent},
  { path: "admin/category", component: ListCategoryComponent},
  { path: "admin/method", component: ListMethodComponent},
  { path: "admin/status", component: ListStatusComponent},
  { path: "product/:id", component: ProductComponent },
  { path: "category", component: CategoryComponent},
  { path: "cart", component: CartComponent},
  { path: "orders", component: OrderComponent}
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
