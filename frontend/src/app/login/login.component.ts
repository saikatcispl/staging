import { Component, OnInit } from '@angular/core';
import { BackendapiService } from '../backendapi.service';
import { Title } from '@angular/platform-browser';
import { ActivatedRoute, Router } from '@angular/router';
import { NgForm } from '@angular/forms';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {
  pageTitle: string;
  model: any = {};
  success_msg: string = '';
  error_msg: string = '';
  membership_uemail: string = '';
  _this: any;

  public constructor(
    private router: Router,
    private titleService: Title,
    private route: ActivatedRoute,
    private backendapiService: BackendapiService
  ) { }

  onSubmit(f: NgForm, router) {
    this.success_msg = '';
    this.error_msg = '';
    this.backendapiService.postLoginForm('postLoginForm', this.model).subscribe(
      resp => {
        if (resp.type == 'success') {
          var resetForm = <HTMLFormElement>document.getElementById('frmForm');
          resetForm.reset();
          this.success_msg = resp.msg;
          localStorage.setItem('membership_uemail', resp.user_email);
          this.router.navigate(['/contactus']);
        }
        if (resp.type == 'error') {
          this.error_msg = resp.msg;
        }
      }
    );
    // alert('SUCCESS!! :-)\n\n' + JSON.stringify(this.model))
  }

  onClickSignupLink(){
    this.router.navigate(['/signup']);
  }

  ngOnInit() {
    this.membership_uemail = localStorage.getItem("membership_uemail");
    console.log(this.membership_uemail);
    if (this.membership_uemail) {
      this.router.navigate(['/contactus']);
    }

    window.scrollTo(0, 0);
    this.pageTitle = this.backendapiService.getSiteName();
    this.titleService.setTitle('Login');
  }

}
