<?php
namespace Tests\Feature;
use App\Models\User;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class ExpenseTrackerTest extends TestCase {
    use RefreshDatabase;
    public function test_dashboard_loads(): void {
        $u = User::factory()->create();
        $this->actingAs($u)->get('/dashboard')->assertStatus(200)->assertSee('Dashboard');
    }
    public function test_category_crud(): void {
        $u = User::factory()->create();
        $this->actingAs($u)->post('/categories', ['name'=>'Food','type'=>'expense','color'=>'#ff0000'])->assertRedirect();
        $this->assertDatabaseHas('categories',['name'=>'Food','user_id'=>$u->id]);
        $c = Category::first();
        $this->actingAs($u)->delete("/categories/{$c->id}")->assertRedirect();
        $this->assertDatabaseMissing('categories',['id'=>$c->id]);
    }
    public function test_transaction_flow_and_summary(): void {
        $u = User::factory()->create();
        $catInc = Category::create(['user_id'=>$u->id,'name'=>'Salary','type'=>'income','color'=>'#10b981']);
        $catExp = Category::create(['user_id'=>$u->id,'name'=>'Rent','type'=>'expense','color'=>'#ef4444']);
        $this->actingAs($u)->post('/transactions', ['title'=>'Pay','amount'=>1000,'type'=>'income','category_id'=>$catInc->id,'transacted_at'=>now()->toDateString()])->assertSessionHasNoErrors();
        $this->actingAs($u)->post('/transactions', ['title'=>'Rent Pay','amount'=>400,'type'=>'expense','category_id'=>$catExp->id,'transacted_at'=>now()->toDateString()])->assertSessionHasNoErrors();
        $this->assertDatabaseCount('transactions',2);
        $this->actingAs($u)->get('/transactions')->assertStatus(200)->assertSee('Pay');
        $this->actingAs($u)->get('/summary?month='.now()->format('Y-m'))->assertStatus(200)->assertSee('Savings');
        $this->actingAs($u)->get('/dashboard')->assertStatus(200)->assertSee('$');
    }
    public function test_category_type_mismatch_rejected(): void {
        $u = User::factory()->create();
        $cat = Category::create(['user_id'=>$u->id,'name'=>'Food','type'=>'expense','color'=>'#ef4444']);
        $this->actingAs($u)->post('/transactions', ['title'=>'Bad','amount'=>10,'type'=>'income','category_id'=>$cat->id,'transacted_at'=>now()->toDateString()])->assertSessionHasErrors('category_id');
    }
}
