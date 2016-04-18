Given(/^I am about to log out$/) do

	visit("https://localhost/login/")
	find('#login-username').set('test@gmail.com')
	find('#login-password').set('test')
	page.execute_script("$('#login-button').click()")
end


When(/^I click on the log out button$/) do
	expect(current_path).to eq '/'
	page.execute_script("$('.show-side toggle-side fa fa-bars').click()")
	page.execute_script("$('.logout side-option').click()")
end

Then(/^the page goes back to login page$/) do
	expect(current_path).to eq '/login/' 

end
