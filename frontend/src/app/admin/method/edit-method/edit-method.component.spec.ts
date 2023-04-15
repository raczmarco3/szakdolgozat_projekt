import { ComponentFixture, TestBed } from '@angular/core/testing';

import { EditMethodComponent } from './edit-method.component';

describe('EditMethodComponent', () => {
  let component: EditMethodComponent;
  let fixture: ComponentFixture<EditMethodComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ EditMethodComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(EditMethodComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
