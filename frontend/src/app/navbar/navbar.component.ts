import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-navbar',
  templateUrl: './navbar.component.html',
  styleUrls: ['./navbar.component.css']
})
export class NavbarComponent implements OnInit {
  authenticated: any = false;
  membership_uemail: string;
  constructor() { }

  ngOnInit() {
    this.membership_uemail = localStorage.getItem("membership_uemail");
    // console.log(this.membership_uemail);
    if (this.membership_uemail) {
      this.authenticated = true;
    }
  }

}
