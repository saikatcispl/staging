import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { LandingPageContentComponent } from './landing-page-content/landing-page-content.component';
import { ProductComponent } from './product/product.component';
import { ProductDetailsComponent } from './product-details/product-details.component';
import { ErrorComponent } from './error/error.component';
import { CmsComponent } from './cms/cms.component';
import { ContactusComponent } from './contactus/contactus.component';
import { ProductIndexComponent } from './product-index/product-index.component';
import { LoginComponent } from './login/login.component';
import { SignupComponent } from './signup/signup.component';
import { MembershipComponent } from './membership/membership.component';
import { MembershipDetailsComponent } from './membership-details/membership-details.component';

const routes: Routes = [
  { path: '', component: LandingPageContentComponent },  
  { path: 'login', component: LoginComponent },  
  { path: 'signup', component: SignupComponent },  
  { path: 'cms/:slug', component: CmsComponent },
  { path: 'contactus', component: ContactusComponent },
  { path: 'products', component: ProductComponent },  
  { path: 'products/:id', component: ProductDetailsComponent },
  { path: 'productsIndex', component: ProductIndexComponent },
  { path: 'membership', component: MembershipComponent },
  { path: 'membershipDetails/:id', component: MembershipDetailsComponent },
  { path: '**', component: ErrorComponent }
];


@NgModule({
  imports: [
    RouterModule.forRoot(routes)
  ],
  exports: [RouterModule],
  declarations: []
})
export class AppRoutingModule {
}



