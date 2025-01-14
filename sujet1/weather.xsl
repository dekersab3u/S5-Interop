<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:template match="/">
        <div>
            <h2>Prévisions météo pour la journée</h2>
            <div>
                <h3>Matin</h3>
                <p>
                    Température :
                    <xsl:value-of select="format-number(previsions/echeance[1]/temperature/level[@val='2m'] - 273.15, '0.0')"/>
                    °C
                </p>
                <p>Pluie : <xsl:value-of select="previsions/echeance[1]/pluie"/> mm</p>
                <p>Vent : <xsl:value-of select="previsions/echeance[1]/vent_moyen/level[@val='10m']"/> km/h</p>
            </div>
            <div>
                <h3>Soir</h3>
                <p>
                    Température :
                    <xsl:value-of select="format-number(previsions/echeance[2]/temperature/level[@val='2m'] - 273.15, '0.0')"/>
                    °C
                </p>
                <p>Pluie : <xsl:value-of select="previsions/echeance[2]/pluie"/> mm</p>
                <p>Vent : <xsl:value-of select="previsions/echeance[2]/vent_moyen/level[@val='10m']"/> km/h</p>
            </div>
        </div>
    </xsl:template>
</xsl:stylesheet>
