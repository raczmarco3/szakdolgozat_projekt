import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import {MainPageComponent} from "./main-page/main-page.component";
import {RegistrationComponent} from "./registration/registration.component";
import {IndexComponent} from "./admin/index/index.component";
import {ListCategoryComponent} from "./admin/category/list-category/list-category.component";
import {ListMethodComponent} from "./admin/method/list-method/list-method.component";

const routes: Routes = [
  { path: '', component: MainPageComponent },
  { path: "register", component: RegistrationComponent },
  { path: "admin", component: IndexComponent},
  { path: "admin/category", component: ListCategoryComponent},
  { path: "admin/method", component: ListMethodComponent}
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
