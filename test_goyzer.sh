#!/bin/bash

curl -X POST "http://webapi.goyzer.com/Company.asmx/SalesListings" \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "AccessCode=\$m\$MzRRe@alE\$t@tE&GroupCode=5114&Bedrooms=0&StartPriceRange=0&EndPriceRange=999999999&CategoryID=0&SpecialProjects=0&CountryID=1&StateID=0&CommunityID=0&DistrictID=0&FloorAreaMin=0&FloorAreaMax=999999&PropertyType=&UnitPK=0&PropertyPK=0&SubCommunityID=0&UnitView=&FittingFixtures=&UnitSubType=&FloorNumber=0&UnitModel=&PrimaryView=&SecondaryView=&Bathrooms=0&MaintenanceFee=0&Freehold=&Leasehold=&PermitNumber=&HandoverDate=&ListingDate=&LastUpdated=&UnitStatus=&AudioTour=&USP=&BranchName=&BranchPhone=&ListingAgentRegistrationNo=&RentPerMonth=0&RentPerAnnum=0&UnitMeasure=&CompanyId=0&CompanyEmail=&CompanyPhone=&CompanyName=&CompanyLogo=&GroupWebUrl=&CompanyRegistrationNo=" \
  --max-time 10 | head -50