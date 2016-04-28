Given(/^I see transactions$/) do
	visit("https://localhost/login/")
	find('#login-username').set('test@gmail.com')
	find('#login-password').set('test')
	page.execute_script("$('#login-button').click()")
	list = Array.new
	list = find('#transaction-list').all('li')
	@size = list.size
end

When(/^I click on button$/) do
	page.execute_script("$(find('.account-option.toggle-list.icon.icon-ios-list')).first.click")
end

Then(/^the size decreases$/) do
	list2 = Array.new
	list2 = find('#transaction-list').all('li')
	@size2 = list2.size
	#@size.should be > size2
end