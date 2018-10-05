import { Component, OnInit } from '@angular/core';
import { BackendapiService } from '../backendapi.service';
import { Title } from '@angular/platform-browser';
import { ActivatedRoute, Router } from '@angular/router';

interface response1 {
  data: any;
}

@Component({
  selector: 'app-membership',
  templateUrl: './membership.component.html',
  styleUrls: ['./membership.component.css']
})
export class MembershipComponent implements OnInit {
  package_list: response1;
  baseurl: any;
  sitename: string;
  currentPageNo: string;
  membership_uemail: string = '';
  authenticated: any = false;

  public constructor(
    private router: Router,
    private titleService: Title,
    private backendapiService: BackendapiService
  ) { }

  routeMembershipDetails(membershipId){
    this.router.navigate(['/membershipDetails/'+membershipId]);
    console.log(membershipId);
  }

  ngOnInit() {
    window.scrollTo(0, 0);
    this.membership_uemail = localStorage.getItem("membership_uemail");
    if (this.membership_uemail) {
      this.authenticated = true;
    }
    this.backendapiService.getProducts('getMembershipPackages?media=1').subscribe(res => {
      this.package_list = res.data.data;
      this.currentPageNo = res.data.current_page;
    });
    this.baseurl = this.backendapiService.getBaseUrl();
    this.sitename = this.backendapiService.getSiteName();
    this.titleService.setTitle(this.sitename + ' | Membership Packages');
  }

}
