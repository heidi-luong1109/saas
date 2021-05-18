<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes();

Route::get('/register/{lang?}', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register')->name('register');
Route::get('/login/{lang?}', 'Auth\LoginController@showLoginForm')->name('login');
Route::get('/password/resets/{lang?}', 'Auth\LoginController@showLinkRequestForm')->name('change.langPass');


Route::get('/', 'DashboardController@index')->name('dashboard')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::get('/dashboard', 'DashboardController@index')->name('dashboard')->middleware(
    [
        'auth',
        'XSS',
    ]
);


Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){
    Route::resource('employee', 'EmployeeController');
}
);
Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){
    Route::resource('client', 'ClientController');
}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){
    Route::resource('department', 'DepartmentController');
}
);
Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){
    Route::resource('designation', 'DesignationController');
}
);
Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){
    Route::resource('salaryType', 'SalaryTypeController');
}
);
Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){
    Route::get('bulk-attendance', 'AttendanceController@bulkAttendance')->name('bulk.attendance');
    Route::post('bulk-attendance', 'AttendanceController@bulkAttendanceData')->name('bulk.attendance');

    Route::post('employee/attendance', 'AttendanceController@attendance')->name('employee.attendance');
    Route::resource('attendance', 'AttendanceController');
}
);
Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){
    Route::resource('holiday', 'HolidayController');
}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){
    Route::get('leave/{id}/action', 'LeaveController@action')->name('leave.action');
    Route::post('leave/changeAction', 'LeaveController@changeAction')->name('leave.changeaction');
    Route::post('leave/jsonCount', 'LeaveController@jsonCount')->name('leave.jsoncount');
    Route::resource('leave', 'LeaveController');
}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){
    Route::resource('leaveType', 'LeaveTypeController');
}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){
    Route::resource('meeting', 'MeetingController');
}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){
    Route::get('lead/list', 'LeadController@lead_list')->name('lead.list');
    Route::post('lead/json', 'LeadController@json')->name('lead.json');
    Route::post('lead/order', 'LeadController@order')->name('lead.order');
    Route::get('lead/{id}/users', 'LeadController@userEdit')->name('lead.users.edit');
    Route::put('lead/{id}/users', 'LeadController@userUpdate')->name('lead.users.update');
    Route::delete('lead/{id}/users/{uid}', 'LeadController@userDestroy')->name('lead.users.destroy');

    Route::get('lead/{id}/items', 'LeadController@productEdit')->name('lead.items.edit');
    Route::put('lead/{id}/items', 'LeadController@productUpdate')->name('lead.items.update');
    Route::delete('lead/{id}/items/{uid}', 'LeadController@productDestroy')->name('lead.items.destroy');

    Route::post('lead/{id}/file', 'LeadController@fileUpload')->name('lead.file.upload');
    Route::get('lead/{id}/file/{fid}', 'LeadController@fileDownload')->name('lead.file.download');
    Route::delete('lead/{id}/file/delete/{fid}', 'LeadController@fileDelete')->name('lead.file.delete');

    Route::get('lead/{id}/sources', 'LeadController@sourceEdit')->name('lead.sources.edit');
    Route::put('lead/{id}/sources', 'LeadController@sourceUpdate')->name('lead.sources.update');
    Route::delete('lead/{id}/sources/{uid}', 'LeadController@sourceDestroy')->name('lead.sources.destroy');

    Route::get('lead/{id}/discussions', 'LeadController@discussionCreate')->name('lead.discussions.create');
    Route::post('lead/{id}/discussions', 'LeadController@discussionStore')->name('lead.discussion.store');

    Route::get('lead/{id}/call', 'LeadController@callCreate')->name('lead.call.create');
    Route::post('lead/{id}/call', 'LeadController@callStore')->name('lead.call.store');
    Route::get('lead/{id}/call/{cid}/edit', 'LeadController@callEdit')->name('lead.call.edit');
    Route::put('lead/{id}/call/{cid}', 'LeadController@callUpdate')->name('lead.call.update');
    Route::delete('lead/{id}/call/{cid}', 'LeadController@callDestroy')->name('lead.call.destroy');

    Route::get('lead/{id}/email', 'LeadController@emailCreate')->name('lead.email.create');
    Route::post('lead/{id}/email', 'LeadController@emailStore')->name('lead.email.store');

    Route::get('lead/{id}/label', 'LeadController@labels')->name('lead.label');
    Route::post('lead/{id}/label', 'LeadController@labelStore')->name('lead.label.store');


    Route::get('lead/{id}/show_convert', 'LeadController@showConvertToDeal')->name('lead.convert.deal');
    Route::post('lead/{id}/convert', 'LeadController@convertToDeal')->name('lead.convert.to.deal');

    Route::post('lead/{id}/note', 'LeadController@noteStore')->name('lead.note.store');

    Route::get('lead/{id}/show_convert', 'LeadController@showConvertToDeal')->name('lead.convert.deal');
    Route::post('lead/{id}/convert', 'LeadController@convertToDeal')->name('lead.convert.to.deal');

    Route::post('lead/change-pipeline', 'LeadController@changePipeline')->name('lead.change.pipeline');
    Route::resource('lead', 'LeadController');
}
);
Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){
    Route::resource('pipeline', 'PipelineController');
}
);


Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){

    Route::post('leadStage/order', 'LeadStageController@order')->name('leadStage.order');
    Route::resource('leadStage', 'LeadStageController');
}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){
    Route::resource('source', 'SourceController');
}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){

    Route::resource('label', 'LabelController');
}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){
    Route::resource('taxRate', 'TaxRateController');
}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){
    Route::resource('unit', 'UnitController');
}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){
    Route::resource('category', 'CategoryController');
}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){

    Route::post('deal/order', 'DealController@order')->name('deal.order');
    Route::get('deal/{id}/users', 'DealController@userEdit')->name('deal.users.edit');
    Route::put('deal/{id}/users', 'DealController@userUpdate')->name('deal.users.update');
    Route::delete('deal/{id}/users/{uid}', 'DealController@userDestroy')->name('deal.users.destroy');

    Route::get('deal/{id}/items', 'DealController@productEdit')->name('deal.items.edit');
    Route::put('deal/{id}/items', 'DealController@productUpdate')->name('deal.items.update');
    Route::delete('deal/{id}/items/{uid}', 'DealController@productDestroy')->name('deal.items.destroy');

    Route::post('deal/{id}/file', 'DealController@fileUpload')->name('deal.file.upload');
    Route::get('deal/{id}/file/{fid}', 'DealController@fileDownload')->name('deal.file.download');
    Route::delete('deal/{id}/file/delete/{fid}', 'DealController@fileDelete')->name('deal.file.delete');


    Route::get('deal/{id}/task', 'DealController@taskCreate')->name('deal.tasks.create');
    Route::post('deal/{id}/task', 'DealController@taskStore')->name('deal.tasks.store');
    Route::get('deal/{id}/task/{tid}/show', 'DealController@taskShow')->name('deal.tasks.show');
    Route::get('deal/{id}/task/{tid}/edit', 'DealController@taskEdit')->name('deal.tasks.edit');
    Route::put('deal/{id}/task/{tid}', 'DealController@taskUpdate')->name('deal.tasks.update');
    Route::put('deal/{id}/task_status/{tid}', 'DealController@taskUpdateStatus')->name('deal.tasks.update_status');
    Route::delete('deal/{id}/task/{tid}', 'DealController@taskDestroy')->name('deal.tasks.destroy');

    Route::get('deal/{id}/products', 'DealController@productEdit')->name('deal.products.edit');
    Route::put('deal/{id}/products', 'DealController@productUpdate')->name('deal.products.update');
    Route::delete('deal/{id}/products/{uid}', 'DealController@productDestroy')->name('deal.products.destroy');

    Route::get('deal/{id}/sources', 'DealController@sourceEdit')->name('deal.sources.edit');
    Route::put('deal/{id}/sources', 'DealController@sourceUpdate')->name('deal.sources.update');
    Route::delete('deal/{id}/sources/{uid}', 'DealController@sourceDestroy')->name('deal.sources.destroy');

    Route::get('deal/{id}/discussions', 'DealController@discussionCreate')->name('deal.discussions.create');
    Route::post('deal/{id}/discussions', 'DealController@discussionStore')->name('deal.discussion.store');

    Route::get('deal/{id}/call', 'DealController@callCreate')->name('deal.call.create');
    Route::post('deal/{id}/call', 'DealController@callStore')->name('deal.call.store');
    Route::get('deal/{id}/call/{cid}/edit', 'DealController@callEdit')->name('deal.call.edit');
    Route::put('deal/{id}/call/{cid}', 'DealController@callUpdate')->name('deal.call.update');
    Route::delete('deal/{id}/call/{cid}', 'DealController@callDestroy')->name('deal.call.destroy');

    Route::get('deal/{id}/email', 'DealController@emailCreate')->name('deal.email.create');
    Route::post('deal/{id}/email', 'DealController@emailStore')->name('deal.email.store');

    Route::get('deal/{id}/clients', 'DealController@clientEdit')->name('deal.clients.edit');
    Route::put('deal/{id}/clients', 'DealController@clientUpdate')->name('deal.clients.update');
    Route::delete('deal/{id}/clients/{uid}', 'DealController@clientDestroy')->name('deal.clients.destroy');

    Route::get('deal/{id}/labels', 'DealController@labels')->name('deal.labels');
    Route::post('deal/{id}/labels', 'DealController@labelStore')->name('deal.labels.store');

    Route::post('deal/{id}/note', 'DealController@noteStore')->name('deal.note.store');
    Route::get('deal/list', 'DealController@deal_list')->name('deal.list');
    Route::post('deal/change-pipeline', 'DealController@changePipeline')->name('deal.change.pipeline');

    Route::post('deal/change-deal-status/{id}', 'DealController@changeStatus')->name('deal.change.status')->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::resource('deal', 'DealController');
}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){

    Route::post('dealStage/order', 'DealStageController@order')->name('dealStage.order');
    Route::post('dealStage/json', 'DealStageController@json')->name('dealStage.json');
    Route::resource('dealStage', 'DealStageController');
}
);


Route::get('estimate/preview/{template}/{color}', 'EstimateController@previewEstimate')->name('estimate.preview');
Route::post('estimate/template/setting', 'EstimateController@saveEstimateTemplateSettings')->name('estimate.template.setting');
Route::get('estimate/pdf/{id}', 'EstimateController@pdf')->name('estimate.pdf')->middleware(['XSS']);
Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){
    Route::post('estimate/product/destroy', 'EstimateController@productDestroy')->name('estimate.product.destroy');
    Route::post('estimate/product', 'EstimateController@product')->name('estimate.product');
    Route::get('estimate/{id}/send', 'EstimateController@send')->name('estimate.send');
    Route::get('estimate/{id}/status', 'EstimateController@statusChange')->name('estimate.status.change');
    Route::get('estimate/items', 'EstimateController@items')->name('estimate.items');

    Route::get('estimate/{id}/convert', 'EstimateController@convert')->name('estimate.convert');
    Route::resource('estimate', 'EstimateController');
}
);


Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){

    Route::post('business-setting', 'SettingController@saveBusinessSettings')->name('business.setting');
    Route::post('company-setting', 'SettingController@saveCompanySettings')->name('company.setting');
    Route::post('email-setting', 'SettingController@saveEmailSettings')->name('email.setting');
    Route::post('system-setting', 'SettingController@saveSystemSettings')->name('system.setting');
    Route::post('pusher-setting', 'SettingController@savePusherSettings')->name('pusher.setting');
    Route::post('payment-setting', 'SettingController@savePaymentSettings')->name('payment.setting');
    Route::post('company-payment-setting', 'SettingController@saveCompanyPaymentSettings')->name('company.payment.setting');

    Route::get('test-mail', 'SettingController@testMail')->name('test.mail');
    Route::post('test-mail', 'SettingController@testSendMail')->name('test.send.mail');

    Route::get('settings', 'SettingController@index')->name('settings');
}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){

    Route::get('project/{project}/user', 'ProjectController@projectUser')->name('project.user');
    Route::post('project/{project}/user', 'ProjectController@addProjectUser')->name('project.user.add');
    Route::delete('project/{project}/user/{user}/destroy', 'ProjectController@destroyProjectUser')->name('project.user.destroy');
    Route::post('project/{project}/status', 'ProjectController@changeStatus')->name('project.status');

    Route::get('project/{id}/task', 'ProjectController@taskBoard')->name('project.task');
    Route::get('project/{id}/task/create', 'ProjectController@taskCreate')->name('project.task.create');
    Route::post('project/{id}/task/store', 'ProjectController@taskStore')->name('project.task.store');
    Route::get('project/task/{id}/edit', 'ProjectController@taskEdit')->name('project.task.edit');
    Route::put('project/task/{id}/update', 'ProjectController@taskUpdate')->name('project.task.update');
    Route::delete('project/task/{id}/delete', 'ProjectController@taskDestroy')->name('project.task.destroy');
    Route::get('project/task/{id}/show', 'ProjectController@taskShow')->name('project.task.show');
    Route::post('project/order', 'ProjectController@order')->name('project.task.order');

    Route::post('project/task/{id}/checklist/store', 'ProjectController@checkListStore')->name('project.task.checklist.store');
    Route::put('project/task/{id}/checklist/{cid}/update', 'ProjectController@checklistUpdate')->name('project.task.checklist.update');
    Route::delete('project/task/{id}/checklist/{cid}', 'ProjectController@checklistDestroy')->name('project.task.checklist.destroy');

    Route::post('project/{id}/task/{tid}/comment', 'ProjectController@commentStore')->name('project.task.comment.store');
    Route::post('project/task/{id}/file', 'ProjectController@commentStoreFile')->name('project.task.comment.file.store');
    Route::delete('project/task/comment/{id}', 'ProjectController@commentDestroy')->name('project.task.comment.destroy');
    Route::delete('project/task/file/{id}', 'ProjectController@commentDestroyFile')->name('project.task.comment.file.destroy');

    Route::get('project/{id}/milestone', 'ProjectController@milestone')->name('project.milestone.create');
    Route::post('project/{id}/milestone', 'ProjectController@milestoneStore')->name('project.milestone.store');
    Route::get('project/milestone/{id}/edit', 'ProjectController@milestoneEdit')->name('project.milestone.edit');
    Route::put('project/milestone/{id}', 'ProjectController@milestoneUpdate')->name('project.milestone.update');
    Route::delete('project/milestone/{id}', 'ProjectController@milestoneDestroy')->name('project.milestone.destroy');
    Route::get('project/milestone/{id}/show', 'ProjectController@milestoneShow')->name('project.milestone.show');
    Route::get('project/task', 'ProjectController@task')->name('project.task');

    Route::get('project/{id}/note', 'ProjectController@notes')->name('project.note.create');
    Route::post('project/{id}/note', 'ProjectController@noteStore')->name('project.note.store');
    Route::get('project/{pid}/note/{id}', 'ProjectController@noteEdit')->name('project.note.edit');
    Route::put('project/{pid}/note/{id}', 'ProjectController@noteupdate')->name('project.note.update');
    Route::delete('project/{pid}/note/{id}', 'ProjectController@noteDestroy')->name('project.note.destroy');

    Route::get('project/{id}/file', 'ProjectController@file')->name('project.file.create');
    Route::post('project/{id}/file', 'ProjectController@fileStore')->name('project.file.store');
    Route::get('project/{pid}/file/{id}', 'ProjectController@fileEdit')->name('project.file.edit');
    Route::put('project/{pid}/file/{id}', 'ProjectController@fileupdate')->name('project.file.update');
    Route::delete('project/{pid}/file/{id}', 'ProjectController@fileDestroy')->name('project.file.destroy');


    Route::post('project/{id}/comment', 'ProjectController@projectCommentStore')->name('project.comment.store');
    Route::get('project/{id}/comment', 'ProjectController@projectComment')->name('project.comment.create');
    Route::get('project/{id}/comment/{cid}/reply', 'ProjectController@projectCommentReply')->name('project.comment.reply');


    Route::post('project/{id}/client/feedback', 'ProjectController@projectClientFeedbackStore')->name('project.client.feedback.store');
    Route::get('project/{id}/client/feedback', 'ProjectController@projectClientFeedback')->name('project.client.feedback.create');
    Route::get('project/{id}/client/feedback/{cid}/reply', 'ProjectController@projectClientFeedbackReply')->name('project.client.feedback.reply');


    Route::get('project/{id}/timesheet', 'ProjectController@projectTimesheet')->name('project.timesheet.create');
    Route::post('project/{id}/timesheet', 'ProjectController@projectTimesheetStore')->name('project.timesheet.store');
    Route::get('project/{id}/timesheet/{tid}/edit', 'ProjectController@projectTimesheetEdit')->name('project.timesheet.edit');
    Route::put('project/{id}/timesheet{tid}/edit', 'ProjectController@projectTimesheetUpdate')->name('project.timesheet.update');
    Route::delete('project/{pid}/timesheet/{id}', 'ProjectController@projectTimesheetDestroy')->name('project.timesheet.destroy');
    Route::get('project/{id}/timesheet/{tid}/note', 'ProjectController@projectTimesheetNote')->name('project.timesheet.note');


    Route::get('project/timesheet', 'ProjectController@timesheet')->name('project.timesheet');

    //    For Project All Task
    Route::get('project/allTask', 'ProjectController@allTask')->name('project.all.task');
    Route::post('project/milestone', 'ProjectController@getMilestone')->name('project.getMilestone');
    Route::post('project/user', 'ProjectController@getUser')->name('project.getUser');

    //    For Project All Task
    Route::get('project/allTimesheet', 'ProjectController@allTimesheet')->name('project.all.timesheet');
    Route::post('project/task', 'ProjectController@getTask')->name('project.getTask');

    Route::resource('project', 'ProjectController')->middleware(
        [
            'auth',
            'XSS',
        ]
    );

}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){
    Route::resource('projectStage', 'ProjectStageController');
    Route::post('projectStage/order', 'ProjectStageController@order')->name('projectStage.order');
}
);

Route::resource('payment', 'PaymentController')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){

    Route::get('creditNote/invoice', 'CreditNoteController@getinvoice')->name('invoice.get');
    Route::resource('creditNote', 'CreditNoteController');
}
);
Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){
    Route::resource('expense', 'ExpenseController');
}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){
    Route::get('contract/{id}/description', 'ContractController@description')->name('contract.description');
    Route::resource('contract', 'ContractController');
}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){
    Route::resource('contractType', 'ContractTypeController');
}
);


Route::resource('noticeBoard', 'NoticeBoardController')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){
    Route::resource('goal', 'GoalController');
}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){
    Route::resource('note', 'NoteController');
}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){
    Route::post('event/employee', 'EventController@getEmployee')->name('event.employee');
    Route::resource('event', 'EventController');
}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){
    Route::get('support/{id}/reply', 'SupportController@reply')->name('support.reply');
    Route::post('support/{id}/reply', 'SupportController@replyAnswer')->name('support.reply.answer');
    Route::resource('support', 'SupportController');
}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){
    Route::resource('plan', 'PlanController');
}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){

    Route::get('order', 'StripePaymentController@index')->name('order.index');
    Route::get('/stripe/{code}', 'StripePaymentController@stripe')->name('stripe');
    Route::post('/stripe', 'StripePaymentController@stripePost')->name('stripe.post');

}
);


Route::post('plan-pay-with-paypal', 'PaypalController@planPayWithPaypal')->name('plan.pay.with.paypal')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::get('{id}/plan-get-payment-status', 'PaypalController@planGetPaymentStatus')->name('plan.get.payment.status')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::post('{id}/pay-with-paypal', 'PaypalController@clientPayWithPaypal')->name('client.pay.with.paypal')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('{id}/get-payment-status', 'PaypalController@clientGetPaymentStatus')->name('client.get.payment.status')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::post('invoice/{id}/payment', 'StripePaymentController@addpayment')->name('client.invoice.payment')->middleware(
    [
        'auth',
        'XSS',
    ]
);


Route::get(
    '/apply-coupon', [
                       'as' => 'apply.coupon',
                       'uses' => 'CouponController@applyCoupon',
                   ]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);


Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){
    Route::resource('coupon', 'CouponController');
}
);

Route::get(
    '/apply-coupon', [
                       'as' => 'apply.coupon',
                       'uses' => 'CouponController@applyCoupon',
                   ]
)->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::put('change-password', 'UserController@updatePassword')->name('update.password');

//========================================HR===============================

Route::resource('account-assets', 'AssetController')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::resource('document-upload', 'DocumentUploadController')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::resource('company-policy', 'CompanyPolicyController')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::resource('award', 'AwardController')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::resource('transfer', 'TransferController')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::resource('award-type', 'AwardTypeController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('resignation', 'ResignationController')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::resource('trip', 'TripController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('promotion', 'PromotionController')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::resource('complaint', 'ComplaintController')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::resource('warning', 'WarningController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('termination', 'TerminationController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('termination-type', 'TerminationTypeController')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::resource('indicator', 'IndicatorController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post('employee/json', 'EmployeeController@json')->name('employee.json')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('appraisal', 'AppraisalController')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::resource('training-type', 'TrainingTypeController')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::resource('trainer', 'TrainerController')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::put('training/status', 'TrainingController@updateStatus')->name('training.status')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('training', 'TrainingController')->middleware(
    [
        'auth',
        'XSS',
    ]
);


//========================================OLD===============================
Route::get('profile', 'UserController@profile')->name('profile')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::put('edit-profile', 'UserController@editprofile')->name('update.account')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::put('edit-client-profile', 'UserController@clientCompanyEdit')->name('client.update.company')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::resource('users', 'UserController')->middleware(
    [
        'auth',
        'XSS',
    ]
);


Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){
    Route::resource('unit', 'UnitController');
}
);
Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){
    Route::resource('paymentMethod', 'PaymentMethodController');
}
);

//Route::group(
//    [
//        'middleware' => [
//            'auth',
//            'XSS',
//        ],
//    ], function (){
//    Route::resource('role-type', 'RoleTypeController');
//}
//);
//Route::group(
//    [
//        'middleware' => [
//            'auth',
//            'XSS',
//        ],
//    ], function (){
//    Route::resource('ticket-type', 'TicketTypeController');
//}
//);


Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){
    Route::resource('user', 'UserController');
}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){
    Route::resource('item', 'ItemController');
}
);
Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){
    Route::resource('proposal', 'ProposalController');
}
);


Route::get('invoice/preview/{template}/{color}', 'InvoiceController@previewInvoice')->name('invoice.preview');
Route::post('invoice/template/setting', 'InvoiceController@saveInvoiceTemplateSettings')->name('invoice.template.setting');
Route::get('invoice/pdf/{id}', 'InvoiceController@pdf')->name('invoice.pdf')->middleware(['XSS']);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){
    Route::post('invoice/client/project', 'InvoiceController@getClientProject')->name('invoice.client.project');
    Route::get('invoice/{id}/item/create', 'InvoiceController@createItem')->name('invoice.create.item');
    Route::post('invoice/{id}/product/store', 'InvoiceController@storeProduct')->name('invoice.store.product');
    Route::post('invoice/{id}/project/store', 'InvoiceController@storeProject')->name('invoice.store.project');
    Route::get('invoice/{id}/send', 'InvoiceController@send')->name('invoice.send');
    Route::get('invoice/{id}/receipt/create', 'InvoiceController@createReceipt')->name('invoice.create.receipt');
    Route::post('invoice/{id}/receipt/store', 'InvoiceController@storeReceipt')->name('invoice.store.receipt');
    Route::delete('invoice/{id}/payment/{pid}', 'InvoiceController@paymentDelete')->name('invoice.payment.delete');
    Route::get('invoice/{id}/status', 'InvoiceController@statusChange')->name('invoice.status.change');

    Route::get('invoice/item', 'InvoiceController@items')->name('invoice.items');
    Route::delete('invoice/{id}/item/{pid}', 'InvoiceController@itemDelete')->name('invoice.item.delete');


    Route::resource('invoice', 'InvoiceController');
}
);


Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){
    Route::get('task-report', 'ReportController@task')->name('report.task');
    Route::get('timelog-report', 'ReportController@timelog')->name('report.timelog');
    Route::get('finance-report', 'ReportController@finance')->name('report.finance');
    Route::get('income-expense-report', 'ReportController@incomeVsExpense')->name('report.income.expense');
    Route::get('leave-report', 'ReportController@leave')->name('report.leave');

    Route::get('employee/{id}/leave/{status}/{type}/{month}/{year}', 'ReportController@employeeLeave')->name('report.employee.leave')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::get('estimate-report', 'ReportController@estimate')->name('report.estimate');
    Route::get('invoice-report', 'ReportController@invoice')->name('report.invoice');
    Route::get('lead-report', 'ReportController@lead')->name('report.lead');
    Route::get('client-report', 'ReportController@client')->name('report.client');
    Route::get('attendance-report', 'ReportController@attendance')->name('report.attendance');
}
);


Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){
    Route::get('change-language/{lang}', 'LanguageController@changeLanquage')->name('change.language')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::get('manage-language/{lang}', 'LanguageController@manageLanguage')->name('manage.language')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::post('store-language-data/{lang}', 'LanguageController@storeLanguageData')->name('store.language.data')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::get('create-language', 'LanguageController@createLanguage')->name('create.language')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::post('store-language', 'LanguageController@storeLanguage')->name('store.language')->middleware(
        [
            'auth',
            'XSS',
        ]
    );

    Route::delete('/lang/{lang}', 'LanguageController@destroyLang')->name('lang.destroy')->middleware(
        [
            'auth',
            'XSS',
        ]
    );
}
);

Route::get('user/{id}/plan', 'UserController@upgradePlan')->name('plan.upgrade')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('user/{id}/plan/{pid}', 'UserController@activePlan')->name('plan.active')->middleware(
    [
        'auth',
        'XSS',
    ]
);


// Email Templates
Route::get('email_template_lang/{id}/{lang?}', 'EmailTemplateController@manageEmailLang')->name('manage.email.language')->middleware(['auth']);
Route::put('email_template_store/{pid}', 'EmailTemplateController@storeEmailLang')->name('store.email.language')->middleware(['auth']);
Route::put('email_template_status/{id}', 'EmailTemplateController@updateStatus')->name('status.email.language')->middleware(['auth']);

Route::resource('email_template', 'EmailTemplateController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::resource('email_template_lang', 'EmailTemplateLangController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
