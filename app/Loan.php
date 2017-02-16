<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    //
	protected $table = 'loan_application';
	// Mass assignable fields
	 protected $fillable = [
        'applicant_type', 'bond_type', 'property_type', 'property_address', 'bond_month', 'bond_outs', 'current_value'
		, 'purchase_price', 'bond_granted', 'legal_atcion', 'judgement_atcion', 'judgement_details', 'liquidation_atcion', 'liquidation_details'
		, 'debt_review', 'debt_review_details', 'exis_inst', 'exis_branch', 'exis_acc', 'exis_amount', 'exis_curr', 'exis_inst1', 'exis_branch1'
		, 'exis_acc1', 'exis_amount1', 'exis_curr1', 'bank_inst', 'bank_branch', 'bank_acc_type', 'bank_acc', 'bank_acc_name', 'bank_inst1'
		, 'bank_branch1', 'bank_acc_type1', 'bank_acc1', 'bank_acc_name1', 'digital_signature', 'submitted', 'created_date', 'amount_wanted', 'loan_period'
    ];
	
	 //Relationship loan and company
    public function loanCompany() {
        return $this->hasOne(loan_company::class, 'loan_id');
    }
	
	//Relationship loan and documents
    public function loanDoc() {
        return $this->hasmany(loan_documents::class, 'loan_id');
    } 
	
	//Relationship loan and History
    public function loanHistory() {
        return $this->hasOne(loan_employment_history::class, 'loan_id');
    } 
	
	//Relationship loan and insurance
    public function loanInsurance() {
        return $this->hasOne(loan_insurance::class, 'loan_id');
    } 
	
	//Relationship loan and liabilities
    public function loanLiabilities() {
        return $this->hasOne(loan_liability::class, 'loan_id');
    } 
	
	//Relationship loan and status
    public function loanStatus() {
        return $this->hasOne(loan_status::class, 'loan_id');
    } 
	
	//Relationship loan and assets
    public function loanAssets() {
        return $this->hasOne(loan_assets::class, 'loan_id');
    }
	
	//Relationship loan and income
    public function loanIncome() {
        return $this->hasOne(loan_income::class, 'loan_id');
    }
	//Relationship loan and income
    public function loanContacts() {
        return $this->hasOne(loan_application_contacts::class, 'loan_id');
    }
    //Relationship loan and loan application status trail
    public function loanStatusTrail() {
        return $this->hasMany(LoanApplicationStatusTrail::class, 'loan_id');
    }
    //Relationship loan and interest rate
    public function interestRate() {
        return $this->hasOne(InterestRate::class, 'loan_id');
    }
    //Relationship loan and loan statements
    public function loanStatement() {
        return $this->hasMany(LoanStatement::class, 'loan_id');
    }

    //Function to save loan's company
    public function addCompany(loan_company $person) {
        return $this->loanCompany()->save($person);
    }
    public function addOrUpdateCompany(array $company) {
        return $this->loanCompany()->updateOrCreate([], $company);
    }
	
    //Function to save loan's hr company
    public function addDocs(loan_documents $person) {
        return $this->loanDoc()->save($person);
    }
    public function addOrUpdateDocs(array $document) {
        return $this->loanDoc()->updateOrCreate([], $document);
    }
	
    //Function to save loan's hr company
    public function addHistory(loan_employment_history $person) {
        return $this->loanHistory()->save($person);
    }
    public function addOrUpdateHistory(array $history) {
        return $this->loanHistory()->updateOrCreate([], $history);
    }
	
    //Function to save loan's hr company
    public function addInsurance(loan_insurance $person) {
        return $this->loanInsurance()->save($person);
    }
    public function addOrUpdateInsurance(array $insurance) {
        return $this->loanInsurance()->updateOrCreate([], $insurance);
    }
	
    //Function to save loan's liabilities
    public function addLiabilities(loan_liability $person) {
        return $this->loanLiabilities()->save($person);
    }
    public function addOrUpdateLiabilities(array $liability) {
        return $this->loanLiabilities()->updateOrCreate([], $liability);
    }
	
    //Function to save loan's hr company
    public function addStatus(loan_status $person) {
        return $this->loanStatus()->save($person);
    }
    public function addOrUpdateStatus(array $status) {
        return $this->loanStatus()->updateOrCreate([], $status);
    }
	
    //Function to save loan's hr company
    public function addAssets(loan_assets $person) {
        return $this->loanAssets()->save($person);
    }
    public function addOrUpdateAssets(array $assets) {
        return $this->loanAssets()->updateOrCreate([], $assets);
    }
	
    //Function to save loan's hr company
    public function addIncome(loan_income $person) {
        return $this->loanIncome()->save($person);
    }
    public function addOrUpdateIncome(array $income) {
        return $this->loanIncome()->updateOrCreate([], $income);
    }
    
	//Function to save loan's hr company
    public function addContacts(loan_application_contacts $person) {
        return $this->loanContacts()->save($person);
    }
    public function addOrUpdateContracts(array $contract) {
        return $this->loanContacts()->updateOrCreate([], $contract);
    }

    //Function to save loan's status trail
    public function addStatusTrsail(LoanApplicationStatusTrail $statusTrail) {
        return $this->loanStatusTrail()->save($statusTrail);
    }

    //Function to save loan's interest rate
    public function addInterestRate(InterestRate $interestRate) {
        return $this->interestRate()->save($interestRate);
    }

    //Function to save loan statement record
    public function addLoanStatement(LoanStatement $loanStatement) {
        return $this->loanStatement()->save($loanStatement);
    }
}
