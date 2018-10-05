import { Component, OnInit } from '@angular/core';
import { BackendapiService } from '../backendapi.service';
import { ActivatedRoute } from '@angular/router';
import { Title } from '@angular/platform-browser';

@Component({
  selector: 'app-membership-details',
  templateUrl: './membership-details.component.html',
  styleUrls: ['./membership-details.component.css']
})
export class MembershipDetailsComponent implements OnInit {

  membership_details: any;
  baseurl: any;
  membershipId: string;
  siteName: string;
  membership_uemail: string = '';
  authenticated: any = false;

  constructor(
    private titleService: Title,
    private route: ActivatedRoute,
    private backendapiService: BackendapiService
  ) { }

  ngOnInit() {
    this.membership_uemail = localStorage.getItem("membership_uemail");
    if (this.membership_uemail) {
      this.authenticated = true;
    }
    this.membershipId = this.route.snapshot.paramMap.get('id');
    this.backendapiService.getProduct('getMembershipPackage', this.membershipId).subscribe(res => {
      this.membership_details = res.data;
    });
    this.baseurl = this.backendapiService.getBaseUrl();
    this.siteName = this.backendapiService.getSiteName();
    this.titleService.setTitle(this.siteName+' | Membership Details');
  }

}
