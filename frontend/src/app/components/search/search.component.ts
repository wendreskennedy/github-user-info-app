import { Component, EventEmitter, Output } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

@Component({
  standalone: true,
  selector: 'app-search',
  imports: [CommonModule, FormsModule],
  templateUrl: './search.component.html',
  styleUrls: ['./search.component.css'],
})
export class SearchComponent {
  username = '';

  @Output() search = new EventEmitter<string>();

  onSubmit() {
    if (this.username.trim()) {
      this.search.emit(this.username.trim());
    }
  }
}
