import { Component, OnInit } from '@angular/core';
import { BackendapiService } from '../backendapi.service';
import { Title }     from '@angular/platform-browser';

@Component({
  selector: 'app-landing-page-content',
  templateUrl: './landing-page-content.component.html',
  styleUrls: ['./landing-page-content.component.css']
})
export class LandingPageContentComponent implements OnInit {

  pageTitle:string;

  public constructor(
    private titleService: Title,
    private backendapiService: BackendapiService
  ) { }
 
 
  ngOnInit() {
    this.pageTitle = this.backendapiService.getSiteName();
    this.titleService.setTitle( this.pageTitle );
  }

}
