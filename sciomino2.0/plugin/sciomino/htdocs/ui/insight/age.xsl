<?xml version="1.0"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" />

<xsl:variable name="CSS_PREFIX" select="'puu-'" />
<xsl:variable name="CLS_LABEL" select="concat($CSS_PREFIX, 'lbl')" />
<xsl:variable name="FULL">
	<xsl:choose>
		<xsl:when test="/table/@data-full">
			<xsl:value-of select="/table/@data-full" />
		</xsl:when>
		<xsl:otherwise>
			160
		</xsl:otherwise>
	</xsl:choose>
</xsl:variable>
<xsl:variable name="SIZE" select="'height:'" />
<xsl:variable name="PX" select="'px'" />
<xsl:variable name="MAX">
	<xsl:for-each select="/table//tr">
		<xsl:sort select="sum(td)" order="descending" data-type="number" />
		<xsl:if test="position() = 1">
			<xsl:value-of select="sum(td)" />
		</xsl:if>
	</xsl:for-each>
</xsl:variable>

<xsl:template match="//*">
    <xsl:element name="{name()}">
        <xsl:apply-templates select="* | @* | text()"/>
    </xsl:element>
</xsl:template>

<xsl:template match="//@*">
    <xsl:attribute name="{name(.)}">
        <xsl:value-of select="."/>
    </xsl:attribute>
</xsl:template>

<xsl:template match="table" priority="1">
	<xsl:element name="div">
		<xsl:apply-templates select="* | @* | text()" />
	</xsl:element>
</xsl:template>

<xsl:template match="tr" priority="1">
	<xsl:element name="div">
		<xsl:for-each select="td">
			<xsl:element name="div">
				<xsl:attribute name="style">
					<xsl:value-of select="concat($SIZE, $FULL * . div $MAX, $PX)" />
				</xsl:attribute>
				<xsl:apply-templates select="* | @* | text()"/>
			</xsl:element>
		</xsl:for-each>
		<xsl:for-each select="th">
			<xsl:element name="div">
				<xsl:attribute name="class">
					<xsl:value-of select="$CLS_LABEL" />
				</xsl:attribute>
				<xsl:apply-templates select="* | @* | text()"/>
			</xsl:element>
		</xsl:for-each>
	</xsl:element>
</xsl:template>

<xsl:template match="th" priority="1">
	<xsl:element name="div">
		<xsl:apply-templates select="* | @* | text()"/>
	</xsl:element>
</xsl:template>

<xsl:template match="td" priority="1">
	<xsl:element name="div">
		<xsl:apply-templates select="* | @* | text()"/>
	</xsl:element>
</xsl:template>

<xsl:template match="tbody" priority="1">
	<xsl:apply-templates select="* | @* | text()"/>
</xsl:template>

</xsl:stylesheet>
