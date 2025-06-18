import { ComponentFixture, TestBed } from '@angular/core/testing';

import { FollowingsListComponent } from './followings-list.component';

describe('FollowingsListComponent', () => {
  let component: FollowingsListComponent;
  let fixture: ComponentFixture<FollowingsListComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [FollowingsListComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(FollowingsListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
