import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import {MainPageComponent} from "./main-page/main-page.component";
import {RegistrationComponent} from "./registration/registration.component";
import {IndexComponent} from "./admin/index/index.component";

const routes: Routes = [
  { path: '', component: MainPageComponent },
  { path: "register", component: RegistrationComponent },
  { path: "admin", component: IndexComponent}
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
